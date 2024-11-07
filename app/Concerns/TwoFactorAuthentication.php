<?php

namespace App\Concerns;

use App\Mail\RecoveryCodeRegenerated;
use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Eye\CompositeEye;
use BaconQrCode\Renderer\Eye\ModuleEye;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Module\RoundnessModule;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

trait TwoFactorAuthentication
{
    /**
     * Get the user's two factor authentication recovery codes.
     *
     * @return array<int, string>
     */
    public function recoveryCodes(): array
    {
        if (! isset($this->two_factor_recovery_codes)) {
            return [];
        }

        return json_decode(decrypt($this->two_factor_recovery_codes), true);
    }

    /**
     * Generate a new recovery code.
     */
    public function generateRecoveryCode(): string
    {
        return Str::random(10).'-'.Str::random(10);
    }

    /**
     * Replace the given recovery code with a new one in the user's stored codes.
     */
    public function replaceRecoveryCode(string $code): void
    {
        $this->forceFill([
            'two_factor_recovery_codes' => encrypt(str_replace(
                $code,
                $this->generateRecoveryCode(),
                decrypt($this->two_factor_recovery_codes)
            )),
        ])->save();

        // TODO
        // Mail::to($this)->send(new RecoveryCodeRegenerated($this, $code));
    }

    /**
     * Get the QR code SVG of the user's two factor authentication QR code URL.
     */
    public function twoFactorQrCodeSvg(bool $dark = false): string
    {
        $svg = (new Writer(
            new ImageRenderer(
                new RendererStyle(
                    225,
                    0,
                    new RoundnessModule(RoundnessModule::SOFT),
                    new CompositeEye(
                        new ModuleEye(new RoundnessModule(RoundnessModule::SOFT)),
                        new ModuleEye(new RoundnessModule(RoundnessModule::SOFT)),
                    ),
                    Fill::uniformColor(
                        $dark ? new Rgb(34, 34, 39) : new Rgb(255, 255, 255),
                        $dark ? new Rgb(229, 231, 235) : new Rgb(45, 55, 72)
                    )
                ),
                new SvgImageBackEnd
            )
        ))->writeString($this->twoFactorQrCodeUrl());

        return trim(substr($svg, strpos($svg, "\n") + 1));
    }

    /**
     * Get the two factor authentication QR code URL.
     */
    public function twoFactorQrCodeUrl(): string
    {
        return app(Google2FA::class)->getQRCodeUrl(
            config('app.name'),
            $this->email,
            decrypt($this->two_factor_secret)
        );
    }

    /**
     * Check the two factor enabled status.
     */
    public function twoFactorEnabled(): bool
    {
        return isset($this->two_factor_secret);
    }
}
