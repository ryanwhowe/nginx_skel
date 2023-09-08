<?php

namespace App\Entity;

class Flash {

    /** @deprecated This should not be used, please use ALERT_INFO instead */
    const ALERT_PRIMARY = 'primary';
    /** @deprecated This should not be used, please use ALERT_INFO instead */
    const ALERT_SECONDARY = 'secondary';

    const ALERT_SUCCESS = 'success';
    const ALERT_DANGER = 'danger';
    const ALERT_WARNING = 'warning';
    const ALERT_INFO = 'info';

    /** @deprecated This should not be used */
    const ALERT_LIGHT = 'light';
    /** @deprecated This should not be used */
    const ALERT_DARK = 'dark';

}