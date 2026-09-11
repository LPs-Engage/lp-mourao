import AppLayout from '@/layouts/app-layout';
import AuthLayout from '@/layouts/auth-layout';
import LandingLayout from '@/layouts/landing-layout';
import SettingsLayout from '@/layouts/settings/layout';
import SiteLayout from '@/layouts/site-layout';

export function resolvePageLayout(name: string) {
    switch (true) {
        case name === 'welcome':
            return null;
        case name.startsWith('site/'):
            return SiteLayout;
        case name.startsWith('landing-pages/'):
            return LandingLayout;
        case name.startsWith('auth/'):
            return AuthLayout;
        case name.startsWith('settings/'):
            return [AppLayout, SettingsLayout];
        default:
            return AppLayout;
    }
}
