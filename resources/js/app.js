// require('./bootstrap');

import Alpine from 'alpinejs';
import Tooltip from '@ryangjchandler/alpine-tooltip';
import FormsAlpinePlugin from '../../vendor/filament/forms/dist/module.esm';
import Trap from '@alpinejs/trap';

Alpine.plugin(FormsAlpinePlugin);
Alpine.plugin(Trap);
Alpine.plugin(Tooltip);

window.Alpine = Alpine;

Alpine.start();
