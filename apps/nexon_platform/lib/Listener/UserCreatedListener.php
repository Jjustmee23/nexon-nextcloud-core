<?php
/**
 * SPDX-FileCopyrightText: 2026 Nexon Solutions
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
declare(strict_types=1);

namespace OCA\NexonPlatform\Listener;

use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\User\Events\BeforeUserCreatedEvent;
use OCP\User\Events\UserCreatedEvent;
use Psr\Log\LoggerInterface;

/**
 * @template-implements IEventListener<BeforeUserCreatedEvent|UserCreatedEvent>
 */
class UserCreatedListener implements IEventListener {
	/** @var array<string, true> */
	private static array $pendingProvision = [];

	public function __construct(
		private LoggerInterface $logger,
	) {
	}

	public static function markPending(string $uid): void {
		self::$pendingProvision[$uid] = true;
	}

	public static function consumePending(string $uid): bool {
		if (!isset(self::$pendingProvision[$uid])) {
			return false;
		}
		unset(self::$pendingProvision[$uid]);
		return true;
	}

	public function handle(Event $event): void {
		if ($event instanceof BeforeUserCreatedEvent) {
			if (method_exists($event, 'getEmail')) {
				$email = trim((string)$event->getEmail());
				if ($email === '' || !str_contains($email, '@')) {
					throw new \InvalidArgumentException(
						'Email is required for platform provisioning (Identity + mail).'
					);
				}
			}
			return;
		}

		if (!$event instanceof UserCreatedEvent) {
			return;
		}

		$user = $event->getUser();
		$uid = $user->getUID();
		$email = trim((string)$user->getEMailAddress());
		if ($email === '') {
			$this->logger->warning(
				'nexon_platform: user {uid} created without email — set email to enable Identity + mail',
				['uid' => $uid]
			);
		}
		self::markPending($uid);
	}
}
