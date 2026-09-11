import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';

type PremiumButtonProps = {
    href: string;
    children: React.ReactNode;
    variant?: 'primary' | 'secondary' | 'outline';
    className?: string;
    external?: boolean;
};

export default function PremiumButton({
    href,
    children,
    variant = 'primary',
    className,
    external = false,
}: PremiumButtonProps) {
    const baseClasses =
        'inline-flex items-center justify-center rounded-[10px] px-6 py-3 text-sm font-bold tracking-wide uppercase transition-colors focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-mourao-yellow';

    const variantClasses = {
        primary: 'bg-mourao-orange text-white hover:bg-transparent hover:text-mourao-orange border border-mourao-orange',
        secondary: 'bg-mourao-navy text-white hover:bg-mourao-blue',
        outline: 'border border-mourao-navy text-mourao-navy hover:bg-mourao-navy hover:text-white',
    };

    const classes = cn(baseClasses, variantClasses[variant], className);

    if (external || href.startsWith('http') || href.startsWith('mailto:') || href.startsWith('tel:')) {
        return (
            <a href={href} className={classes} rel={href.startsWith('http') ? 'noopener noreferrer' : undefined}>
                {children}
            </a>
        );
    }

    return (
        <Link href={href} className={classes}>
            {children}
        </Link>
    );
}
