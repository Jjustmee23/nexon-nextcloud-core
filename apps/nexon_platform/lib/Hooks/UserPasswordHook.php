<?php
/**
 * SPDX-FileCopyrightText: 2026 Nexon Solutions
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
declare(strict_types=1);

namespace OCA\NexonPlatform\Hooks;

use OCA\NexonPlatform\Listener\UserCreatedListener;
use OCA\NexonPlatform\Service\PlatformClient;
use OCP\IUserManager;
use OCP\Server;
use Psr\Log\LoggerInterface;

class UserPasswordHook {
	/**
	 * @param array{uid?: string} $params
	 */
	public static function postCreateUser(array $params): void {
		$uid = (string)($params['uid'] ?? '');
		if ($uid === '') {
			return;
		}
		UserCreatedListener::markPending($uid);
	}

	/**
	 * @param array{uid?: string, password?: string} $params
	 */
	public static function postSetPassword(array $params): void {
		$uid = (string)($params['uid'] ?? '');
		$password = (string)($params['password'] ?? '');
		if ($uid === '' || $password === '') {
			return;
		}

		/** @var PlatformClient $client */
		$client = Server::get(PlatformClient::class);
		/** @var IUserManager $userManager */
		$userManager = Server::get(IUserManager::class);
		/** @var LoggerInterface $logger */
		$logger = Server::get(LoggerInterface::class);

		if (UserCreatedListener::consumePending($uid)) {
			$user = $userManager->get($uid);
			if ($user === null) {
				return;
			}
			$email = trim((string)$user->getEMailAddress());
			if ($email === '') {
				$logger->warning('nexon_platform: cannot provision {uid} without email', ['uid' => $uid]);
				return;
			}
			$display = $user->getDisplayName() ?: $uid;
			$client->provisionUser($uid, $email, $display, $password);
			$logger->info('nexon_platform: provisioned new user {uid}', ['uid' => $uid]);
			return;
		}

		$client->syncPassword($uid, $password);
	}
}
