<?php declare(strict_types=1);

namespace LogicException\GermanAdministrativeAreaKeys;

final class ARS
{
    public function __construct(private string $bundesland,
                                private string|null $regierunsbezirk,
                                private string|null $kreis,
                                private string|null $verband,
                                private string|null $gemeinde)
    {
    }

    public function getBundesland(): string
    {
        return $this->bundesland;
    }

    public function getRegierunsbezirk(): string
    {
        return $this->regierunsbezirk ?? "";
    }

    public function getKreis(): string
    {
        return $this->kreis ?? "";
    }

    public function getVerband(): string
    {
        return $this->verband ?? "";
    }

    public function getGemeinde(): string
    {
        return $this->gemeinde ?? "";
    }

    public function __toString(): string
    {
        return $this->getBundesland()
            . $this->getRegierunsbezirk()
            . $this->getKreis()
            . $this->getVerband()
            . $this->getGemeinde();
    }

    public function toAGS(): AGS
    {
        $ags = $this->getBundesland() .
            $this->getRegierunsbezirk() .
            $this->getKreis() .
            $this->getGemeinde();

        return AGS::fromString($ags);
    }

    public static function fromString(string $ars): self|null
    {
        if (false === \preg_match("/^\d{2,12}$/", $ars)) {
            return null;
        }

        $land = self::parseBundeslandSchluessel($ars);
        $len = \strlen($ars);

        $rb = null;
        if ($len >= 3) {
            $rb = self::parseRegierunsbezirkSchluessel($ars);
        }

        $kreis = null;
        if ($len >= 5) {
            $kreis = self::parseKreisSchluessel($ars);
        }

        $verband = null;
        if ($len >= 9) {
            $verband = self::parseVerbandSchluessel($ars);
        }

        $gemeinde = null;
        if ($len === 12) {
            $gemeinde = self::parseGemeindeSchluessel($ars);
        }

        return new self($land, $rb, $kreis, $verband, $gemeinde);
    }

    private static function isVerbandstyp(string $typ): bool {
        return false !== \preg_match(  "/^[059]$/", $typ);
    }

    public function isVerbandsfreieGemeinde(): bool {
        return $this->getVerbandstyp() === "0";
    }

    public function isVerbandsangehoerigeGemeinde(): bool {
        return $this->getVerbandstyp() === "5";
    }

    public function isGemeindefreiesGebiet(): bool {
        return $this->getVerbandstyp() === "9";
    }

    private function getVerbandstyp(): ?string {
        if ($this->verband) {
            $tKennzeichen = $this->verband[0];
            if (self::isVerbandstyp($tKennzeichen)) {
                return $tKennzeichen;
            }
        }

        return null;
    }

    private static function parseBundeslandSchluessel(string $ags): string
    {
        $land = \substr($ags, 0, 2);
        if (!self::isBundeslandSchluessel($land)) {
            throw new \RuntimeException("invalid AGS Bundesland string");
        }
        return $land;
    }

    private static function isBundeslandSchluessel(string $land): bool
    {
        return false !== \preg_match("/^([0][1-9]|[1][0-4])$/", $land);
    }

    private static function parseRegierunsbezirkSchluessel(string $ags): string
    {
        $rb = $ags[2];
        if (!self::isRegierungsbezirkSchluessel($rb)) {
            throw new \RuntimeException("invalid AGS Regierungsbezirk string");
        }
        return $rb;
    }

    private static function isRegierungsbezirkSchluessel(string $rb): bool
    {
        return false !== \preg_match("/^\d$/", $rb);
    }

    private static function parseKreisSchluessel(string $ags): string
    {
        $kreis = \substr($ags, 3, 2);
        if (!self::isKreisSchluessel($kreis)) {
            throw new \RuntimeException("invalid AGS Kreis string");
        }
        return $kreis;
    }

    private static function isKreisSchluessel(string $kreis): bool
    {
        return false !== \preg_match("/^\d{2}$/", $kreis);
    }

    private static function  parseVerbandSchluessel(string $ars): string {
        $verband = \substr($ars, 5, 4);
        if (!self::isVerbandSchluessel($verband)) {
            throw new \RuntimeException("invalid ARS Verband string");
        }

        return $verband;
    }

    private static function  isVerbandSchluessel(string $verband): bool {
        return false !== \preg_match("/^([059])\d{3}$/", $verband);
    }

    private static function parseGemeindeSchluessel(string $ags): string
    {
        $gemeinde = \substr($ags, 9, 3);
        if (!self::isGemeindeSchluessel($gemeinde)) {
            throw new \RuntimeException("invalid AGS Gemeinde string");
        }
        return $gemeinde;
    }

    private static function isGemeindeSchluessel(string $gemeinde): bool
    {
        return false !== \preg_match("/^\d{3}$/", $gemeinde);
    }
}