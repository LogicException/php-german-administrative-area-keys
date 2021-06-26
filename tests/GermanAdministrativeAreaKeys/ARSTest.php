<?php declare(strict_types=1);
namespace LogicException\GermanAdministrativeAreaKeys;
use PHPUnit\Framework\TestCase;

class ARSTest extends TestCase
{
    public function testShouldCanCreateArs() {
        $ars = new ARS("14", "7", "30", "0070", "070");

        $this->assertSame("14", $ars->getBundesland());
        $this->assertSame("7", $ars->getRegierunsbezirk());
        $this->assertSame("30", $ars->getKreis());
        $this->assertSame("0070", $ars->getVerband());
        $this->assertSame("070", $ars->getGemeinde());
    }

    public function testShouldCanUseAgsArString() {
        $ars = new ARS("14", "7", "30", "0070", "070");

        $this->assertSame("147300070070", (string)$ars);
    }

    public function testShouldCanCreateArsFromString() {
        $ags = ARS::fromString("147300070070");

        $this->assertSame("14", $ags->getBundesland());
        $this->assertSame("7", $ags->getRegierunsbezirk());
        $this->assertSame("30", $ags->getKreis());
        $this->assertSame("0070", $ags->getVerband());
        $this->assertSame("070", $ags->getGemeinde());
    }

    public function testShouldCanCreateAgsFromArs() {
        $ars = new ARS("14", "7", "30", "0070", "070");
        $ags = $ars->toAGS();

        $this->assertSame("14730070", (string)$ags);
    }

    public function testShouldCanGetVerbandstypVerbandsfreieGemeindeFromArs() {
        // Delitzsch
        $ars = new ARS("14", "7", "30", "0070", "070");

        $this->assertTrue($ars->isVerbandsfreieGemeinde());
        $this->assertFalse($ars->isVerbandsangehoerigeGemeinde());
        $this->assertFalse($ars->isGemeindefreiesGebiet());
    }

    public function testShouldCanGetVerbandstypVerbandsangehoerigeGemeindeFromArs() {
        // Pirna
        $ars = new ARS("14", "6", "28", "5229", "270");

        $this->assertFalse($ars->isVerbandsfreieGemeinde());
        $this->assertTrue($ars->isVerbandsangehoerigeGemeinde());
        $this->assertFalse($ars->isGemeindefreiesGebiet());
    }

    public function testShouldCanGetVerbandstypGemeindefreiesGebietFromArs() {
        // Sachsenwald
        $ars = ARS::fromString("010539105105");

        $this->assertFalse($ars->isVerbandsfreieGemeinde());
        $this->assertFalse($ars->isVerbandsangehoerigeGemeinde());
        $this->assertTrue($ars->isGemeindefreiesGebiet());
    }
}
