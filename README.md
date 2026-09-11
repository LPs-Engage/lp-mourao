# lp-mourao

Landing da campanha Lucas Mourão (55500), agora em **Laravel 13 + Inertia 3 + React 19 + Vite 8 + Tailwind 4**.

## Requisitos

- PHP 8.3+
- Node 22+
- Composer

## Setup

```bash
composer run setup
composer run dev
```

O site público fica em `/`. Conteúdo editável em `resources/data/site-pages.json`. Landing pages extras em `resources/data/landing-pages.json`.

Leads da home vão para `POST /cadastro`. Em sucesso, a seção Movimento troca o formulário pelo CTA de WhatsApp na mesma página. Webhooks são configurados no `.env` (`HOME_LEAD_WEBHOOK_URL`, `LP_LEAD_WEBHOOK_URL`).
