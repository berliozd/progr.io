import {getActiveLanguage} from "laravel-vue-i18n";
import {format} from 'date-fns'
import {enUS, fr} from 'date-fns/locale'

export default function date(timestamp) {
    const date = new Date(timestamp * 1000);
    return format(date, 'P', {locale: getLocale()});
}

const getLocale = () => {
    switch (getActiveLanguage()) {
        case 'en':
            return enUS;
        case 'fr':
            return fr;
    }
}

export function getShortDatePattern() {
    switch (getActiveLanguage()) {
        case 'en':
            return 'MM/dd/YYYY';
        case 'fr':
            return 'dd/MM/YYYY';
    }
}
