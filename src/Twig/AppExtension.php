<?php
declare(strict_types=1);

namespace App\Twig;

use App\Entity\LikeNotification;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigTest;

final class AppExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var string
     */
    private $locale;

    /**
     * AppExtension constructor.
     *
     * @param string $locale
     */
    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals()
    {
        return [
            'locale' => $this->locale
        ];
    }

    public function getTests()
    {
        return [
            new TwigTest(
                'like',
                static function ($obj) {
                    return $obj instanceof LikeNotification;
                }
            )
        ];
    }
}
