import {
    censorEmail,
    censorPhone,
    censorName,
    censorString,
    censorCard,
    censorId,
} from '@/Utils/censorship';

export function useCensor() {
    return {
        censorEmail,
        censorPhone,
        censorName,
        censorString,
        censorCard,
        censorId,
    };
}
