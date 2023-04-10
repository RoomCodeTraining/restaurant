import Alpine from 'alpinejs'
import FormsAlpinePlugin from '../../vendor/filament/forms/dist/module.esm'
import NotificationsAlpinePlugin from '../../vendor/filament/notifications/dist/module.esm'

import Tooltip from '@ryangjchandler/alpine-tooltip';
import Trap from '@alpinejs/trap';

Alpine.plugin(Trap);
Alpine.plugin(Tooltip);

window.Alpine = Alpine;

Alpine.plugin(FormsAlpinePlugin)
Alpine.plugin(NotificationsAlpinePlugin)

window.Alpine = Alpine

Alpine.start()
