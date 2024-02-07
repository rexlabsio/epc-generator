<?php

namespace Rexlabs\EpcGenerator;

use Intervention\Image\ImageManagerStatic as Image;

class EpcGenerator
{
    /**
     * The current energy assessment.
     *
     * @var string
     */
    protected $energyAssessment = 'epc';

    /**
     * Property address.
     *
     * @var string
     */
    protected $address = null;

    /**
     * Property reference.
     *
     * @var string
     */
    protected $reference = null;

    /**
     * Current Energy Efficiency Rating.
     *
     * @var int
     */
    protected $currentEnergyEfficiencyRating = null;

    /**
     * Potential Energy Efficiency Rating.
     *
     * @var int
     */
    protected $potentialEnergyEfficiencyRating = null;

    /**
     * Current Environmental Impact Rating.
     *
     * @var int
     */
    protected $currentEnvironmentalImpactRating = null;

    /**
     * Potential Environmental Impact Rating.
     *
     * @var int
     */
    protected $potentialEnvironmentalImpactRating = null;

    public function __construct($imageDriver = 'gd')
    {
        Image::configure(['driver' => $imageDriver]);
    }

    /**
     * Set the energy assessment for the generator.
     *
     * @param  string       $energyAssessment The energy assessment. Currently you can choose between EPC and PEA.
     * @return EpcGenerator
     * @throws \Exception
     */
    public function setEnergyAssessment(string $energyAssessment)
    {
        if (!in_array(strtolower($energyAssessment), ['epc', 'pea', 'eer', 'eir'])) {
            throw new Exception('Energy Assessment type can be either EPC or PEA.');
        }
        $this->energyAssessment = strtolower($energyAssessment);

        return $this;
    }

    /**
     * Get the energy assessment for the generator.
     *
     * @return string
     */
    public function energyAssessment()
    {
        return $this->energyAssessment;
    }

    /**
     * Set the property address.
     *
     * @param  string       $address The property address.
     * @return EpcGenerator
     */
    public function setAddress(string $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the property address.
     *
     * @param string $address
     */
    public function address()
    {
        return $this->address;
    }

    /**
     * Set the property reference.
     *
     * @param  string       $reference The property reference.
     * @return EpcGenerator
     */
    public function setReference(string $reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get the property reference.
     *
     * @param string $reference
     */
    public function reference()
    {
        return $this->reference;
    }

    /**
     * Set current energy efficiency rating.
     *
     * @param  int          $value The rating.
     * @return EpcGenerator
     */
    public function setCurrentEnergyEfficiencyRating(int $value)
    {
        $this->currentEnergyEfficiencyRating = $value;

        return $this;
    }

    /**
     * Get current energy efficiency rating.
     *
     * @param int $value
     */
    public function currentEnergyEfficiencyRating()
    {
        return $this->currentEnergyEfficiencyRating;
    }

    /**
     * Set potential energy efficiency rating.
     *
     * @param  int          $value The rating.
     * @return EpcGenerator
     */
    public function setPotentialEnergyEfficiencyRating(int $value)
    {
        $this->potentialEnergyEfficiencyRating = $value;

        return $this;
    }

    /**
     * Get potential energy efficiency rating.
     *
     * @param int $value
     */
    public function potentialEnergyEfficiencyRating()
    {
        return $this->potentialEnergyEfficiencyRating;
    }

    /**
     * Set current environmental impact rating.
     *
     * @param  int          $value The rating.
     * @return EpcGenerator
     */
    public function setCurrentEnvironmentalImpactRating(int $value)
    {
        $this->currentEnvironmentalImpactRating = $value;

        return $this;
    }

    /**
     * Get current environmental impact rating.
     *
     * @param int $value
     */
    public function currentEnvironmentalImpactRating()
    {
        return $this->currentEnvironmentalImpactRating;
    }

    /**
     * Set potential environmental impact rating.
     *
     * @param  integer      $value The rating.
     * @return EpcGenerator
     */
    public function setPotentialEnvironmentalImpactRating(int $value)
    {
        $this->potentialEnvironmentalImpactRating = $value;

        return $this;
    }

    /**
     * Get potential environmental impact rating.
     *
     * @param int $value
     */
    public function potentialEnvironmentalImpactRating()
    {
        return $this->potentialEnvironmentalImpactRating;
    }

    /**
     * Save the EPC report.
     *
     * @param  string  $filename The path to save the report
     * @param  string  $format   The format of the image.  Currently support:jpg, png, gif, tif, bmp, ico, psd, webp, data-url
     * @param  integer $quality  The quality of the image
     * @return Image
     */
    public function save($filename, $format = 'png', $quality = 70)
    {
        $image = $this->prepareImage($format, $quality);

        return $image->save($filename);
    }

    /**
     * Stream the EPC report.
     *
     * @param string $format  Image format
     * @param string $quality Image quality.
     *
     * @return Image
     */
    public function stream($format = 'png', $quality = 70)
    {
        $image = $this->prepareImage($format, $quality);

        return $image->stream($format, $quality);
    }

    /**
     * Prepare the diagram.
     *
     * @param  string  $format
     * @param  integer $quality
     * @return Image
     */
    private function prepareImage($format = 'png', $quality = 70)
    {
        $image = Image::make(__DIR__ . '/../assets/' . $this->energyAssessment() . '.png');

        if ($this->energyAssessment() === 'epc') {
            $image->insert(
                $this->arrow($this->currentEnergyEfficiencyRating(), 'eer'),
                'top-left',
                545,
                $this->height($this->currentEnergyEfficiencyRating())
            );

            $image->insert(
                $this->arrow($this->currentEnvironmentalImpactRating(), 'eir'),
                'top-left',
                1327,
                $this->height($this->currentEnvironmentalImpactRating())
            );

            $image->insert(
                $this->arrow($this->potentialEnergyEfficiencyRating(), 'eer'),
                'top-left',
                635,
                $this->height($this->potentialEnergyEfficiencyRating())
            );

            $image->insert(
                $this->arrow($this->potentialEnvironmentalImpactRating(), 'eir'),
                'top-left',
                1415,
                $this->height($this->potentialEnvironmentalImpactRating())
            );
        }

        if ($this->energyAssessment() === 'pea') {
            $image->insert(
                $this->arrow($this->currentEnergyEfficiencyRating(), 'eer'),
                'top-left',
                545,
                $this->height($this->currentEnergyEfficiencyRating())
            );

            $image->insert(
                $this->arrow($this->currentEnvironmentalImpactRating(), 'eir'),
                'top-left',
                1238,
                $this->height($this->currentEnvironmentalImpactRating())
            );
        }

        if ($this->energyAssessment() === 'eer') {
            $image->insert(
                $this->arrow($this->currentEnergyEfficiencyRating(), 'eer'),
                'top-left',
                545,
                $this->height($this->currentEnergyEfficiencyRating())
            );

            $image->insert(
                $this->arrow($this->potentialEnergyEfficiencyRating(), 'eer'),
                'top-left',
                635,
                $this->height($this->potentialEnergyEfficiencyRating())
            );
        }

        if ($this->energyAssessment() === 'eir') {
            $image->insert(
                $this->arrow($this->currentEnvironmentalImpactRating(), 'eir'),
                'top-left',
                545,
                $this->height($this->currentEnvironmentalImpactRating())
            );

            $image->insert(
                $this->arrow($this->potentialEnvironmentalImpactRating(), 'eir'),
                'top-left',
                635,
                $this->height($this->potentialEnvironmentalImpactRating())
            );
        }


        if (!is_null($this->address())) {
            // Expand the image to have the address under the diagram.
            $original_width = $image->getWidth();
            $original_height = $image->getHeight();
            $image->resizeCanvas($original_width, ($original_height + 65), 'top', false, 'ffffff')
                ->text('Address: ' . $this->address(), 15, ($original_height + 50), function ($font) {
                    $font->file(__DIR__ . '/../assets/Arial.ttf');
                    $font->size(24);
                    $font->color('#000');
                    $font->align('left');
                });
        }

        if (!is_null($this->reference())) {
            $shrink = !is_null($this->address()) ? 15 : 0;
            // Expand the image to have the reference under the diagram.
            $original_width = $image->getWidth();
            $original_height = $image->getHeight();
            $image->resizeCanvas($original_width, ($original_height + (65 - $shrink)), 'top', false, 'ffffff')
                ->text('Reference: ' . $this->reference(), 15, ($original_height + (50 - $shrink)), function ($font) {
                    $font->file(__DIR__ . '/../assets/Arial.ttf');
                    $font->size(24);
                    $font->color('#000');
                    $font->align('left');
                });
        }

        return $image->encode($format, $quality);
    }

    /**
     * Get the color for the correspondent rating.
     *
     * @param  int    $value
     * @param  string $type
     * @return string
     */
    private function color($value, $type)
    {
        if ($value >= 92) {
            return $type == 'eer' ? '#127d5a' : '#84c6e7';
        }
        if ($value >= 81) {
            return $type == 'eer' ? '#2ca55a' : '#4aacda';
        }
        if ($value >= 69) {
            return $type == 'eer' ? '#8cbd42' : '#269ad1';
        }
        if ($value >= 55) {
            return $type == 'eer' ? '#f7ce17' : '#1176b9';
        }
        if ($value >= 39) {
            return $type == 'eer' ? '#f4a96a' : '#bebdbb';
        }
        if ($value >= 21) {
            return $type == 'eer' ? '#ed8024' : '#9f9fa0';
        }

        return $type == 'eer' ? '#e4203a' : '#818281';
    }

    /**
     * Draw the arrow.
     *
     * @param  int    $value
     * @param  string $type
     * @return Image
     */
    private function arrow($value, $type)
    {
        $img = Image::canvas(80, 60, '#fff');
        $points = $points = [
            0, 30,
            10, 0,
            80, 0,
            80, 60,
            10, 60,
        ];

        $color = $this->color($value, $type);

        return $img->polygon($points, function ($draw) use ($color) {
            $draw->background($color);
        })->text($value, 40, 30, function ($font) {
            $font->file(__DIR__ . '/../assets/Arial Bold.ttf');
            $font->size(38);
            $font->color('#fff');
            $font->align('center');
            $font->valign('middle');
        });
    }

    /**
     * Get the vertical position of the arrow.
     *
     * @param  int $value
     * @return int
     */
    private function height($value)
    {
        if ($value >= 1 && $value < 21) {
            return intval(582 - (($value - 1) * 58.5 / 20) - 30);
        }

        if ($value >= 21 && $value < 39) {
            return intval(518 - (($value - 21) * 58.5 / 17) - 30);
        }

        if ($value >= 39 && $value < 55) {
            return intval(453 - (($value - 39) * 58.5 / 15) - 30);
        }

        if ($value >= 55 && $value < 69) {
            return intval(389 - (($value - 55) * 58.5 / 13) - 30);
        }

        if ($value >= 69 && $value < 81) {
            return intval(325 - (($value - 69) * 58.5 / 11) - 30);
        }

        if ($value >= 81 && $value < 92) {
            return intval(261 - (($value - 81) * 58.5 / 10) - 30);
        }

        if ($value >= 92) {
            $value = $value > 100 ? 100 : $value;
            return intval(197 - (($value - 92) * 58.5 / 10) - 30);
        }
    }
}
