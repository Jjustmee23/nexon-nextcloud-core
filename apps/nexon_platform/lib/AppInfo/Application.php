<?php
/**
 * SPDX-FileCopyrightText: 2026 Nexon Solutions
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
declare(strict_types=1);

namespace OCA\NexonPlatform\AppInfo;

use OCA\NexonPlatform\Listener\UserCreatedListener;
use OCA\NexonPlatform\Listener\UserChangedListener;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\User\Events\BeforeUserCreatedEvent;
use OCP\User\Events\UserChangedEvent;
use OCP\User\Events\UserCreatedEvent;
use OCP\Util;

class Application extends App implements IBootstrap {
	public const APP_ID = 'nexon_platform';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
		$context->registerEventListener(BeforeUserCreatedEvent::class, UserCreatedListener::class);
		$context->registerEventListener(UserCreatedEvent::class, UserCreatedListener::class);
		$context->registerEventListener(UserChangedEvent::class, UserChangedListener::class);
	}

	public function boot(IBootContext $context): void {
		Util::connectHook('OC_User', 'post_createUser', '\OCA\NexonPlatform\Hooks\UserPasswordHook', 'postCreateUser');
		Util::connectHook('OC_User', 'post_setPassword', '\OCA\NexonPlatform\Hooks\UserPasswordHook', 'postSetPassword');
	}
}
