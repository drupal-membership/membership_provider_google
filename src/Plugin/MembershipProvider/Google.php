<?php

declare(strict_types=1);

namespace Drupal\membership_provider_google\Plugin\MembershipProvider;

use Drupal\Core\Form\FormStateInterface;
use Drupal\membership\Entity\MembershipInterface;
use Drupal\membership\Plugin\MembershipProviderBase;

/**
 * Apple membership provider.
 *
 * @MembershipProvider(
 *   id = "google",
 *   label = "Google Play",
 * )
 */
final class Google extends MembershipProviderBase {

  /**
   * @inheritDoc
   */
  public function postCreateMembership(MembershipInterface $membership, array $pluginValues = []): void {
    // TODO: Implement postCreateMembership() method.
  }

  /**
   * @inheritDoc
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    return $form;
  }

  /**
   * @inheritDoc
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement validateConfigurationForm() method.
  }

  /**
   * @inheritDoc
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitConfigurationForm() method.
  }

}
