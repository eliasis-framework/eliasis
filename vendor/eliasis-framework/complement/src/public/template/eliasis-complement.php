<?php
/**
 * PHP library for adding addition of complements for Eliasis Framework.
 *
 * @author     Josantonius - hello@josantonius.com
 * @copyright  Copyright (c) 2017
 * @license    https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link       https://github.com/Eliasis-Framework/Module
 * @since      1.0.8
 */

use Eliasis\View\View;

$data = View::get();
?>
<div id="eliasis-complements">

    <transition-group name="list" tag="div">
        <div v-for="error in errors" v-bind:key="error">
            <div class="jst-install-error">
                <p><strong>
                    <span class="jst-error-msg">{{ error.message }}</span>
                </strong></p>
            </div>
        </div>
    </transition-group>

    <div class="mdl-cell mdl-cell--12-col mdl-grid mdl-grid--no-spacing-off">
        <div v-for="(complement, key) in complements" :id="complement.id" class="eliasis-complement mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col-tablet mdl-cell--12-col-phone ">
            <div ></div>
            <div class="mdl-card__title mdl-card--expand mdl-color--blue-200" :style="complement.image_style">
                <a :href="complement.uri" title="" target="_blank">
                    <div class="complement-version">
                        {{ complement.version }}
                    </div>
                </a>
            </div>
            <div class="jst-card--border"></div>
            <div class="mdl-card__supporting-text mdl-color-text--grey-600">
            
                <h2 class="mdl-card__title-text card-title">
                    {{ complement.name }}
                </h2>
                <br>
                {{ complement.description }}
                <div class="mdl-list__item">
                   <div class="custom-fields">
                        <div class="complement-state-btn">
                            <state-buttons :complement-id="complement.id" :complement-state='complement.state' v-model="errors" inline-template>
                                <div class="state-buttons">
                                    <transition name="fade" mode="out-in">
                                        <button v-bind:class="['mdl-button', 'mdl-js-button', 'mdl-button--raised', 'mdl-js-ripple-effect', 'mdl-button--accent', 'mod-btn', 'complement-btn', 'complement-btn-left', { 'complement-btn-active': isActive, 'complement-btn-outdated': isOutdated, 'complement-btn-uninstalled': isUninstalled }]" v-on:click="changeState()" :disabled="isUninstall || isInstall">
                                            <span class="complement-load complement-open complement-load-install" v-if="isInstall"></span>
                                            {{ isInstall ? '' : changeButtonState }}
                                        </button>
                                    </transition>
                                    <transition name="fade" mode="out-in">
                                        <button class=" mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mod-btn mdl-button--accent complement-btn complement-btn-uninstall" v-if="isInactive" v-on:click="uninstall()" :disabled="isUninstall">
                                            <span class="complement-load complement-open complement-load-remove" v-if="isUninstall"></span>
                                            {{ isUninstall ? '' : removeButton }}
                                        </button>
                                    </transition>
                                </div>
                            </state-buttons>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id='complements-filter'
         data-app='<?= $data["app"] ?>'
         data-sort='<?= $data["sort"] ?>'
         data-filter='<?= $data["filter"] ?>'
         data-language='<?= $data["language"] ?>'
         data-external='<?= $data["external"] ?>'
         data-complement='<?= $data["complement"] ?>'>
    </div>

</div>