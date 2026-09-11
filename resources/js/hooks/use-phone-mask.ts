import { useState } from 'react';

export function formatBrazilPhone(value: string): string {
    let digits = value.replace(/\D/g, '');

    if (!digits.startsWith('55')) {
        digits = `55${digits}`;
    }

    digits = digits.slice(0, 13);

    let formatted = '+55 ';

    if (digits.length > 2) {
        formatted += `(${digits.slice(2, 4)}) `;
    }

    if (digits.length > 4) {
        formatted += digits.slice(4, 9);
    }

    if (digits.length > 9) {
        formatted += `-${digits.slice(9)}`;
    }

    return formatted;
}

export function usePhoneMask(initial = '+55 ') {
    const [value, setValue] = useState(initial);

    const onChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setValue(formatBrazilPhone(event.target.value));
    };

    return { value, onChange, setValue };
}
