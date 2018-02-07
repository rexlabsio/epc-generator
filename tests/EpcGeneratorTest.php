<?php

namespace MyWebApplication\EpcGenerator\Tests;

use PHPUnit_Framework_TestCase;
use MyWebApplication\EpcGenerator\EpcGenerator;
use org\bovigo\vfs\vfsStream;

class EpcGeneratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;

    /**
     * set up test environment.
     */
    public function setUp()
    {
        $this->root = vfsStream::setup('exampleDir');
    }

    public function testReportCanBeCreated()
    {
        $report = (new EpcGenerator())
            ->setAddress('1 Test Address, Success street.')
            ->setReference('ABC123')
            ->setCurrentEnergyEfficiencyRating(40)
            ->setPotentialEnergyEfficiencyRating(50)
            ->setCurrentEnvironmentalImpactRating(60)
            ->setPotentialEnvironmentalImpactRating(70);

        $this->assertEquals('1 Test Address, Success street.', $report->address());
        $this->assertEquals('ABC123', $report->reference());
        $this->assertEquals(40, $report->currentEnergyEfficiencyRating());
        $this->assertEquals(50, $report->potentialEnergyEfficiencyRating());
        $this->assertEquals(60, $report->currentEnvironmentalImpactRating());
        $this->assertEquals(70, $report->potentialEnvironmentalImpactRating());
        $this->assertEquals('epc', $report->energyAssessment());
    }

    public function testCanStreamReport()
    {
        $report = (new EpcGenerator())
            ->setAddress('1 Test Address, Success street.')
            ->setReference('ABC123')
            ->setCurrentEnergyEfficiencyRating(40)
            ->setPotentialEnergyEfficiencyRating(50)
            ->setCurrentEnvironmentalImpactRating(60)
            ->setPotentialEnvironmentalImpactRating(70)
            ->stream();
        $this->assertEquals("\x89PNG\x0d\x0a\x1a\x0a", substr($report, 0, 8));
    }

    public function testCanSaveReport()
    {
        $report = (new EpcGenerator())
            ->setAddress('1 Test Address, Success street.')
            ->setReference('ABC123')
            ->setCurrentEnergyEfficiencyRating(40)
            ->setPotentialEnergyEfficiencyRating(50)
            ->setCurrentEnvironmentalImpactRating(60)
            ->setPotentialEnvironmentalImpactRating(70)
            ->save(vfsStream::url('exampleDir/test.png'));

        $this->assertTrue($this->root->hasChild('test.png'));
    }
}
