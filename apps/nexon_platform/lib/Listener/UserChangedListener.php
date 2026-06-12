<?php
/**
 * SPDX-FileCopyrightText: 2026 Nexon Solutions
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
declare(strict_types=1);

namespace OCA\NexonPlatform\Listener;

use OCA\NexonPlatform\Service\PlatformClient;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\User\Events\UserChangedEvent;
use Psr\Log\LoggerInterface;

/**
 * @template-implements IEventListener<UserChangedEvent>
 */
class UserChangedListener implements IEventListener {
	public function __construct(
		private PlatformClient $client,
		private LoggerInterface $logger,
	) {
	}

	public function handle(Event $event): void {
		if (!$event instanceof UserChangedEvent) {
			return;
		}
		$user = $event->getUser();
		$uid = $user->getUID();
		$email = trim((string)$user->getEMailAddress());
		if ($email === '') {
			return;
		}
		$display = $user->getDisplayName() ?: $uid;
		$this->client->provisionUser($uid, $email, $display);
		$this->logger->info('nexon_platform: profile sync for {uid}', ['uid' => $uid]);
	}
}
