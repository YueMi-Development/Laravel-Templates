/**
 * Censorship and Data Masking Utility
 * 
 * Provides flexible masking for sensitive data like emails, phone numbers,
 * credit cards, names, and generic strings.
 */

/**
 * Masks a generic string keeping specified visible characters at start and end.
 *
 * @param {string} str - String to mask
 * @param {Object} options
 * @param {number} [options.visibleStart=1] - Number of characters visible at the start
 * @param {number} [options.visibleEnd=1] - Number of characters visible at the end
 * @param {string} [options.maskChar='*'] - Character used for masking
 * @param {number|null} [options.fixedLength=null] - Fixed mask length, or null to match masked characters
 * @returns {string}
 */
export function censorString(str, { visibleStart = 1, visibleEnd = 1, maskChar = '*', fixedLength = null } = {}) {
    if (!str || typeof str !== 'string') return '';
    const len = str.length;
    if (len <= visibleStart + visibleEnd) {
        return maskChar.repeat(Math.max(1, len));
    }

    const start = str.slice(0, visibleStart);
    const end = visibleEnd > 0 ? str.slice(len - visibleEnd) : '';
    const maskCount = fixedLength !== null ? fixedLength : len - visibleStart - visibleEnd;

    return `${start}${maskChar.repeat(Math.max(1, maskCount))}${end}`;
}

/**
 * Masks an email address (e.g. "user@example.com" -> "u***r@example.com").
 *
 * @param {string} email - Email address to censor
 * @param {Object} options
 * @param {number} [options.visibleStart=1] - Number of username chars visible at the start
 * @param {number} [options.visibleEnd=1] - Number of username chars visible at the end
 * @param {string} [options.maskChar='*'] - Character used for masking
 * @param {number|null} [options.fixedLength=3] - Fixed mask length (default 3 stars, or null for dynamic)
 * @param {boolean} [options.maskDomain=false] - Whether to also partially mask the domain
 * @returns {string}
 */
export function censorEmail(email, {
    visibleStart = 1,
    visibleEnd = 1,
    maskChar = '*',
    fixedLength = 3,
    maskDomain = false,
} = {}) {
    if (!email || typeof email !== 'string') return '';
    const atIndex = email.indexOf('@');
    if (atIndex === -1) {
        return censorString(email, { visibleStart, visibleEnd, maskChar, fixedLength });
    }

    const username = email.slice(0, atIndex);
    const domain = email.slice(atIndex + 1);

    let maskedUsername = '';
    if (username.length <= 2) {
        maskedUsername = username[0] + maskChar;
    } else {
        const uStart = username.slice(0, visibleStart);
        const uEnd = visibleEnd > 0 ? username.slice(username.length - visibleEnd) : '';
        const maskCount = fixedLength !== null ? fixedLength : Math.max(1, username.length - visibleStart - visibleEnd);
        maskedUsername = `${uStart}${maskChar.repeat(maskCount)}${uEnd}`;
    }

    let finalDomain = domain;
    if (maskDomain) {
        const dotIndex = domain.lastIndexOf('.');
        if (dotIndex > 0) {
            const domainName = domain.slice(0, dotIndex);
            const ext = domain.slice(dotIndex);
            finalDomain = `${censorString(domainName, { visibleStart: 1, visibleEnd: 0, maskChar, fixedLength: 3 })}${ext}`;
        }
    }

    return `${maskedUsername}@${finalDomain}`;
}

/**
 * Masks a phone number (e.g. "+1234567890" -> "+123****7890").
 *
 * @param {string} phone - Phone number to censor
 * @param {Object} options
 * @param {number} [options.visibleStart=4] - Digits visible at the beginning (e.g., prefix / country code)
 * @param {number} [options.visibleEnd=4] - Digits visible at the end
 * @param {string} [options.maskChar='*'] - Character used for masking
 * @param {number|null} [options.fixedLength=null] - Fixed mask count, or null to match hidden digits count
 * @returns {string}
 */
export function censorPhone(phone, {
    visibleStart = 4,
    visibleEnd = 4,
    maskChar = '*',
    fixedLength = 4,
} = {}) {
    if (!phone || typeof phone !== 'string') return '';
    
    // Extract non-formatting characters (digits and optional leading +)
    const raw = phone.trim();
    if (raw.length <= visibleStart + visibleEnd) {
        return maskChar.repeat(Math.max(1, raw.length));
    }

    const start = raw.slice(0, visibleStart);
    const end = raw.slice(raw.length - visibleEnd);
    const count = fixedLength !== null ? fixedLength : raw.length - visibleStart - visibleEnd;

    return `${start}${maskChar.repeat(Math.max(1, count))}${end}`;
}

/**
 * Masks a full name (e.g. "Jane Doe" -> "J**e D*e").
 *
 * @param {string} name - Name to censor
 * @param {Object} options
 * @param {'edges'|'initials'|'firstOnly'} [options.mode='edges'] - Masking style
 * @param {string} [options.maskChar='*'] - Character used for masking
 * @returns {string}
 */
export function censorName(name, { mode = 'edges', maskChar = '*' } = {}) {
    if (!name || typeof name !== 'string') return '';

    const parts = name.trim().split(/\s+/);
    if (mode === 'initials') {
        return parts.map(part => `${part[0]}.`).join(' ');
    }

    if (mode === 'firstOnly') {
        return parts.map((part, index) => {
            if (index === 0) return part;
            return `${part[0]}${maskChar.repeat(Math.max(1, part.length - 1))}`;
        }).join(' ');
    }

    // Default 'edges': keep first and last char of each name token
    return parts.map(part => {
        if (part.length <= 2) return part[0] + maskChar;
        return `${part[0]}${maskChar.repeat(part.length - 2)}${part[part.length - 1]}`;
    }).join(' ');
}

/**
 * Masks a credit card / payment card number (e.g. "1234567812345678" -> "•••• •••• •••• 5678").
 *
 * @param {string} cardNumber - Card number
 * @param {Object} options
 * @param {number} [options.visibleEnd=4] - Number of visible trailing digits
 * @param {string} [options.maskChar='•'] - Mask character
 * @returns {string}
 */
export function censorCard(cardNumber, { visibleEnd = 4, maskChar = '•' } = {}) {
    if (!cardNumber || typeof cardNumber !== 'string') return '';
    const digitsOnly = cardNumber.replace(/\D/g, '');
    if (digitsOnly.length < visibleEnd) return maskChar.repeat(4);

    const lastDigits = digitsOnly.slice(-visibleEnd);
    const maskedGroups = '•••• •••• ••••';
    return `${maskedGroups} ${lastDigits}`;
}

/**
 * Masks government / national identification numbers (e.g., NIK / SSN).
 *
 * @param {string} id - Identification number string
 * @param {Object} options
 * @param {number} [options.visibleStart=4]
 * @param {number} [options.visibleEnd=4]
 * @param {string} [options.maskChar='*']
 * @returns {string}
 */
export function censorId(id, { visibleStart = 4, visibleEnd = 4, maskChar = '*' } = {}) {
    return censorString(id, { visibleStart, visibleEnd, maskChar });
}

export default {
    string: censorString,
    email: censorEmail,
    phone: censorPhone,
    name: censorName,
    card: censorCard,
    id: censorId,
};
