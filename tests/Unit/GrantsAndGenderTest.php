<?php

namespace WenexPlatform\PhpSdk\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WenexPlatform\PhpSdkCommon\Core\Action;
use WenexPlatform\PhpSdkCommon\Core\Enums\Gender;
use WenexPlatform\PhpSdkCommon\Core\Resource;
use WenexPlatform\PhpSdkCommon\Interfaces\Auth\Grants\Grant;
use WenexPlatform\PhpSdkCommon\Interfaces\Auth\Grants\GrantDto;
use WenexPlatform\PhpSdkCommon\Interfaces\Auth\Grants\GrantTime;
use WenexPlatform\PhpSdkCommon\Interfaces\Auth\Grants\GrantTimeDto;

final class GrantsAndGenderTest extends TestCase
{
    public function testGenderValuesMatchTheNodeSdk(): void
    {
        self::assertSame('MALE', Gender::MALE->value);
        self::assertSame('FEMALE', Gender::FEMALE->value);
        self::assertSame('UNKNOWN', Gender::UNKNOWN->value);
    }

    public function testGrantPortsTheNodeModel(): void
    {
        $time = new GrantTime();
        $time->cron_exp = '* * * * *';
        $time->duration = 60;

        $grant = new Grant();
        $grant->subject = 'user@example.com';
        $grant->action = Action::Read;
        $grant->object = Resource::AuthApts;
        $grant->time = [$time];

        self::assertSame('user@example.com', $grant->subject);
        self::assertSame(Action::Read, $grant->action);
        self::assertSame(Resource::AuthApts, $grant->object);
        self::assertSame($time, $grant->time[0]);
    }

    public function testGrantDtosAcceptEnumAndStringValues(): void
    {
        $grant = new GrantDto(
            'user@example.com',
            Action::Update,
            Resource::AuthGrants,
            time: [new GrantTimeDto()],
        );

        self::assertSame('user@example.com', $grant->subject);
        self::assertSame(Action::Update, $grant->action);
        self::assertSame(Resource::AuthGrants, $grant->object);
        self::assertNotNull($grant->time);
        self::assertCount(1, $grant->time);
    }
}
