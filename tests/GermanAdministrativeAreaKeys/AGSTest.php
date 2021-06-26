<?php declare(strict_types=1);
namespace LogicException\GermanAdministrativeAreaKeys;
use PHPUnit\Framework\TestCase;

class AGSTest extends TestCase
{
    public function testShouldCanCreateAgs() {
        $ags = new AGS("14", "7", "30", "070");

        $this->assertSame("14", $ags->getBundesland());
        $this->assertSame("7", $ags->getRegierunsbezirk());
        $this->assertSame("30", $ags->getKreis());
        $this->assertSame("070", $ags->getGemeinde());
    }

    public function testShouldCanUseAgsAsString() {
        $ags = new AGS("14", "7", "30", "070");

        $this->assertSame("14730070", (string)$ags);
    }

    public function testShouldCanCreateAgsFromString() {
        $ags = AGS::fromString("14730070");

        $this->assertSame("14", $ags->getBundesland());
        $this->assertSame("7", $ags->getRegierunsbezirk());
        $this->assertSame("30", $ags->getKreis());
        $this->assertSame("070", $ags->getGemeinde());
    }
}