<?php

declare(strict_types=1);

namespace App\Model\Market\UseCase\Categories\Setting\Edit;

use App\Model\Flusher;
use App\Model\Market\Entity\Categories\Setting\Id;
use App\Model\Market\Entity\Categories\Setting\SettingRepository;
use App\Model\Market\Entity\Categories\Setting\Template\Meta;
use App\Model\Market\Entity\Categories\Setting\Template\Seo;
use App\Model\Market\Entity\Categories\Setting\Template\Template;

class Handler
{
    private SettingRepository $settings;
    private Flusher $flusher;

    public function __construct(SettingRepository $settings, Flusher $flusher)
    {
        $this->settings = $settings;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $setting = $this->settings->get(new Id($command->id));

        $setting->edit(new Template(
            new Seo(
                $command->templateSeoHeading,
                $command->templateSeoDescription
            ),
            new Meta(
                $command->templateMetaTitle,
                $command->templateMetaDescription,
                $command->templateMetaOgTitle,
                $command->templateMetaOgDescription
            )
        ));

        $this->flusher->flush();
    }
}
