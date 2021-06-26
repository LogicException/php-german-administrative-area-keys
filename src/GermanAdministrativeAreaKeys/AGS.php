<?php declare(strict_types=1);

namespace LogicException\GermanAdministrativeAreaKeys;

final class AGS
{
    public function __construct(private string $bundesland,
                                private string|null $regierunsbezirk,
                                private string|null $kreis,
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

    public function getGemeinde(): string
    {
        return $this->gemeinde ?? "";
    }

    public function __toString(): string
    {
        return $this->getBundesland()
            . $this->getRegierunsbezirk()
            . $this->getKreis()
            . $this->getGemeinde();
    }

    public static function fromString(string $ags): self|null
    {
        if (false === \preg_match("/^\d{2,8}$/", $ags)) {
            return null;
        }

        $land = self::parseBundeslandSchluessel($ags);
        $len = \strlen($ags);

        $rb = null;
        if ($len >= 3) {
            $rb = self::parseRegierunsbezirkSchluessel($ags);
        }

        $kreis = null;
        if ($len >= 5) {
            $kreis = self::parseKreisSchluessel($ags);
        }

        $gemeinde = null;
        if ($len === 8) {
            $gemeinde = self::parseGemeindeSchluessel($ags);
        }

        return new self($land, $rb, $kreis, $gemeinde);
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

    private static function parseGemeindeSchluessel(string $ags): string
    {
        $gemeinde = \substr($ags, 5, 3);
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