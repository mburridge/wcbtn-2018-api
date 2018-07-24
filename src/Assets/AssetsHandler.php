<?php
/**
 * WordCamp Brighton 2018 workshop plugin (wcbtn-2018-api).
 *
 * @package schlessera/wcbtn-2018-api
 * @licence MIT
 * @link    https://schlessera.github.io/wcbtn-2018
 */

namespace WordCampBrighton\API\Assets;

use WordCampBrighton\API\Exception\InvalidAssetHandle;
use WordCampBrighton\API\Registerable;

/**
 * Class AssetsHandler.
 *
 * @package schlessera/wcbtn-2018-api
 */
final class AssetsHandler implements Registerable {

	/**
	 * Assets known to this asset handler.
	 *
	 * @var array<Asset>
	 */
	private $assets = [];

	/**
	 * Add a single asset to the asset handler.
	 *
	 * @param Asset $asset Asset to add.
	 */
	public function add( Asset $asset ) {
		$this->assets[ $asset->get_handle() ] = $asset;
	}

	/**
	 * Register the current Registerable.
	 *
	 * @return void
	 */
	public function register() {
		foreach ( $this->assets as $asset ) {
			$asset->register();
		}
	}

	/**
	 * Enqueue a single asset based on its handle.
	 *
	 * @param string $handle Handle of the asset to enqueue.
	 *
	 * @throws InvalidAssetHandle If the passed-in asset handle is not valid.
	 */
	public function enqueue_handle( $handle ) {
		if ( ! array_key_exists( $handle, $this->assets ) ) {
			throw InvalidAssetHandle::from_handle( $handle );
		}
		$this->assets[ $handle ]->enqueue();
	}

	/**
	 * Dequeue a single asset based on its handle.
	 *
	 * @param string $handle Handle of the asset to enqueue.
	 *
	 * @throws InvalidAssetHandle If the passed-in asset handle is not valid.
	 */
	public function dequeue_handle( $handle ) {
		if ( ! array_key_exists( $handle, $this->assets ) ) {
			throw InvalidAssetHandle::from_handle( $handle );
		}
		$this->assets[ $handle ]->dequeue();
	}

	/**
	 * Enqueue all assets known to this asset handler.
	 *
	 * @param Asset|null $asset Optional. Asset to enqueue. If omitted, all
	 *                          known assets are enqueued.
	 */
	public function enqueue( Asset $asset = null ) {
		$assets = $asset ? [ $asset ] : $this->assets;
		foreach ( $assets as $asset_object ) {
			$asset_object->enqueue();
		}
	}
}
