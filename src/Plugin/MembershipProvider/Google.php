<?php

declare(strict_types=1);

namespace Drupal\membership_provider_google\Plugin\MembershipProvider;

use Drupal\commerce\Plugin\Field\FieldType\RemoteIdFieldItemList;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\membership\Entity\MembershipInterface;
use Drupal\membership\Plugin\MembershipProviderBase;
use Drupal\membership\Plugin\SupportsCancellationMembershipProviderInterface;
use Google\Auth\Cache\SysVCacheItemPool;
use Google\Client;
use Google\Service\AndroidPublisher;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Apple membership provider.
 *
 * @MembershipProvider(
 *   id = "google",
 *   label = "Google Play",
 * )
 */
final class Google extends MembershipProviderBase implements SupportsCancellationMembershipProviderInterface, ContainerFactoryPluginInterface {

  public const REMOTE_ID_PURCHASE_TOKEN = 'google_play_purchase_token';

  /**
   * {@inheritDoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    array $plugin_definition,
    public readonly AndroidPublisher $androidPublisher,
    public readonly string $packageId,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritDoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    $publisher = new AndroidPublisher(new Client([
      'application_name' => $container->get('config.factory')
        ->get('system.site')->get('name'),
      'cache' => new SysVCacheItemPool([
        // Vary the cache key because we are requesting a scope.
        'variableKey' => SysVCacheItemPool::VAR_KEY + 1,
      ]),
      'use_application_default_credentials' => TRUE,
      'scopes' => [AndroidPublisher::ANDROIDPUBLISHER],
    ]));
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $publisher,
      $container->getParameter('membership_provider_google.package_id'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function postCreateMembership(MembershipInterface $membership, array $pluginValues = []): void {
    // TODO: Implement postCreateMembership() method.
  }

  /**
   * {@inheritDoc}
   */
  public function getWorkflowId(): string {
    return 'membership_google';
  }

  /**
   * {@inheritDoc}
   */
  public function cancel(MembershipInterface $membership) {
    $remoteIdField = $membership->get('remote_id');
    assert($remoteIdField instanceof RemoteIdFieldItemList);
    $this->androidPublisher->purchases_subscriptions->cancel(
      $this->packageId,
      $membership->get('data')->first()->getValue()['productId'],
      $remoteIdField->getByProvider(self::REMOTE_ID_PURCHASE_TOKEN)
    );
  }

}
