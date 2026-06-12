<?php
/**
 * SPDX-FileCopyrightText: 2026 Nexon Solutions
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
declare(strict_types=1);

namespace OCA\NexonPlatform\Service;

use OCA\NexonPlatform\AppInfo\Application;
use OCP\IConfig;
use Psr\Log\LoggerInterface;

class PlatformClient {
	public function __construct(
		private IConfig $config,
		private LoggerInterface $logger,
	) {
	}

	public function provisionUser(
		string $ncUsername,
		string $email,
		string $displayName,
		string $password = '',
	): bool {
		$kcUsername = $this->deriveKcUsername($ncUsername);
		$payload = [
			'slug' => $this->tenantSlug(),
			'kc_username' => $kcUsername,
			'email' => $email,
			'display_name' => $displayName,
			'nc_username' => $ncUsername,
			'password' => $password,
			'source' => 'nextcloud',
		];
		$result = $this->post($this->provisionUrl(), $payload);
		if ($result === null) {
			return false;
		}
		$this->logger->info('nexon_platform: user provisioned for {uid}', ['uid' => $ncUsername]);
		return true;
	}

	public function syncPassword(string $ncUsername, string $password): bool {
		$kcUsername = $this->deriveKcUsername($ncUsername);
		$url = $this->baseUrl() . '/platform-provision/v1/users/sync-password';
		$payload = [
			'kc_username' => $kcUsername,
			'new_password' => $password,
		];
		$result = $this->post($url, $payload);
		if ($result === null) {
			return false;
		}
		$this->logger->info('nexon_platform: password synced for {uid}', ['uid' => $ncUsername]);
		return true;
	}

	private function deriveKcUsername(string $ncUsername): string {
		return str_replace('-', '.', $ncUsername);
	}

	private function tenantSlug(): string {
		return (string)$this->config->getAppValue(Application::APP_ID, 'tenant_slug', '');
	}

	private function provisionUrl(): string {
		$url = (string)$this->config->getAppValue(Application::APP_ID, 'provision_url', '');
		if ($url !== '') {
			return $url;
		}
		return $this->baseUrl() . '/platform-provision/v1/users';
	}

	private function baseUrl(): string {
		$cloud = (string)$this->config->getAppValue(Application::APP_ID, 'cloud_domain', '');
		if ($cloud === '') {
			$cloud = (string)$this->config->getSystemValue('overwritehost', '');
		}
		if ($cloud === '') {
			return 'https://cloud.localhost';
		}
		return 'https://' . $cloud;
	}

	private function secret(): string {
		return (string)$this->config->getAppValue(Application::APP_ID, 'provision_secret', '');
	}

	/**
	 * @param array<string, mixed> $payload
	 * @return array<string, mixed>|null
	 */
	private function post(string $url, array $payload): ?array {
		$secret = $this->secret();
		if ($secret === '') {
			$this->logger->warning('nexon_platform: provision_secret not configured');
			return null;
		}

		$body = json_encode($payload, JSON_THROW_ON_ERROR);
		$context = stream_context_create([
			'http' => [
				'method' => 'POST',
				'header' => "Content-Type: application/json\r\nX-Platform-Secret: {$secret}\r\n",
				'content' => $body,
				'timeout' => 120,
				'ignore_errors' => true,
			],
			'ssl' => [
				'verify_peer' => true,
				'verify_peer_name' => true,
			],
		]);

		$response = @file_get_contents($url, false, $context);
		if ($response === false) {
			$this->logger->error('nexon_platform: API request failed for {url}', ['url' => $url]);
			return null;
		}

		/** @var array<string, mixed>|null $decoded */
		$decoded = json_decode($response, true);
		if (!is_array($decoded)) {
			$this->logger->error('nexon_platform: invalid API response');
			return null;
		}

		if (isset($decoded['error'])) {
			$this->logger->error('nexon_platform: API error — {error}', ['error' => (string)$decoded['error']]);
			return null;
		}

		return $decoded;
	}
}
