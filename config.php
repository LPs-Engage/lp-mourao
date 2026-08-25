<?php
$time = time();

$status_label = [
    'entregue'  => 'Entregue',
    'execucao'  => 'Em execução',
    'planejado' => 'Planejado',
];

$projetos = [
    [
        'status' => 'entregue',
        'tema'   => 'tema-1',
        'title'  => 'Nome do projeto 1',
        'local'  => 'Bairro / Local — XX',
        'desc'   => 'Descrição do projeto em até duas linhas, explicando do que se trata de forma objetiva.',
        'metric' => 'XX mil m²',
        'href'   => '#',
    ],
    [
        'status' => 'execucao',
        'tema'   => 'tema-1',
        'title'  => 'Nome do projeto 2',
        'local'  => 'Bairro / Local — XX',
        'desc'   => 'Descrição do projeto em até duas linhas, explicando do que se trata de forma objetiva.',
        'metric' => 'XX mil m²',
        'href'   => '#',
    ],
    [
        'status' => 'planejado',
        'tema'   => 'tema-2',
        'title'  => 'Nome do projeto 3',
        'local'  => 'Bairro / Local — XX',
        'desc'   => 'Descrição do projeto em até duas linhas, explicando do que se trata de forma objetiva.',
        'metric' => 'XX mil m²',
        'href'   => '#',
    ],
    [
        'status' => 'entregue',
        'tema'   => 'tema-2',
        'title'  => 'Nome do projeto 4',
        'local'  => 'Bairro / Local — XX',
        'desc'   => 'Descrição do projeto em até duas linhas, explicando do que se trata de forma objetiva.',
        'metric' => 'XX mil m²',
        'href'   => '#',
    ],
    [
        'status' => 'execucao',
        'tema'   => 'tema-3',
        'title'  => 'Nome do projeto 5',
        'local'  => 'Bairro / Local — XX',
        'desc'   => 'Descrição do projeto em até duas linhas, explicando do que se trata de forma objetiva.',
        'metric' => 'XX mil m²',
        'href'   => '#',
    ],
    [
        'status' => 'planejado',
        'tema'   => 'tema-3',
        'title'  => 'Nome do projeto 6',
        'local'  => 'Bairro / Local — XX',
        'desc'   => 'Descrição do projeto em até duas linhas, explicando do que se trata de forma objetiva.',
        'metric' => 'XX mil m²',
        'href'   => '#',
    ],
    
];

$trinta_projetos = [];
for ($dia = 1; $dia <= 30; $dia++) {
    $trinta_projetos[] = [
        'dia'   => $dia,
        'tema'  => 'economia',
        'title' => 'Nome do projeto',
        'desc'  => 'Descrição curta do projeto.',
        'href'  => '#',
    ];
}

$eventos = [
    [
        'data'  => '19/08',
        'local' => 'Praia Grande',
        'title' => 'Nome do evento',
        'hora'  => '18h',
        'href'  => '#',
    ],
    [
        'data'  => 'XX/XX',
        'local' => 'Local',
        'title' => 'Nome do evento',
        'hora'  => 'XXh',
        'href'  => '#',
    ],
    [
        'data'  => 'XX/XX',
        'local' => 'Local',
        'title' => 'Nome do evento',
        'hora'  => 'XXh',
        'href'  => '#',
    ],
    [
        'data'  => 'XX/XX',
        'local' => 'Local',
        'title' => 'Nome do evento',
        'hora'  => 'XXh',
        'href'  => '#',
    ],
];

$start = [
    [
        'src' => 'assets/images/start-img-1.png',
        'alt' => 'Lucas Mourão cumprimenta moradores na Vila São Jorge.',
    ],
    [
        'src' => 'assets/images/start-img-2.png',
        'alt' => 'Lucas Mourão em conversa sobre o real sentido da política.',
    ],
    [
        'src' => 'assets/images/start-img-3.png',
        'alt' => 'Lucas Mourão em campanha: três motivos para votar 55500.',
    ],
    [
        'src' => 'assets/images/start-img-4.png',
        'alt' => 'Lucas Mourão em Praia Grande: mais segurança na Baixada.',
    ],
    [
        'src' => 'assets/images/start-img-5.png',
        'alt' => 'Lucas Mourão com a comunidade da Vila São Jorge.',
    ],
];

$capitulo = [
    [
        'icon'     => 'assets/images/capitulo-icon-1.png',
        'title'    => 'Mobilidade',
        'desafio'  => 'Texto a definir.',
        'proposta' => 'Texto a definir.',
        'estado'   => 'Texto a definir.',
        'href'     => '#',
    ],
    [
        'icon'     => 'assets/images/capitulo-icon-2.png',
        'title'    => 'Desenvolvimento',
        'desafio'  => 'Texto a definir.',
        'proposta' => 'Texto a definir.',
        'estado'   => 'Texto a definir.',
        'href'     => '#',
    ],
    [
        'icon'     => 'assets/images/capitulo-icon-3.png',
        'title'    => 'Saúde',
        'desafio'  => 'Texto a definir.',
        'proposta' => 'Texto a definir.',
        'estado'   => 'Texto a definir.',
        'href'     => '#',
    ],
    [
        'icon'     => 'assets/images/capitulo-icon-4.png',
        'title'    => 'Turismo',
        'desafio'  => 'Texto a definir.',
        'proposta' => 'Texto a definir.',
        'estado'   => 'Texto a definir.',
        'href'     => '#',
    ],
    [
        'icon'     => 'assets/images/capitulo-icon-5.png',
        'title'    => 'Segurança',
        'desafio'  => 'Texto a definir.',
        'proposta' => 'Texto a definir.',
        'estado'   => 'Texto a definir.',
        'href'     => '#',
    ],
    [
        'icon'     => 'assets/images/capitulo-icon-6.png',
        'title'    => 'Juventude / Inovação',
        'desafio'  => 'Texto a definir.',
        'proposta' => 'Texto a definir.',
        'estado'   => 'Texto a definir.',
        'href'     => '#',
    ],
];

$fatos = [
    [
        'icon'  => 'dots',
        'title' => 'Fato 01',
        'desc'  => 'Lucas comandou a Secretaria de Projetos Especiais e Estratégicos.',
        'cta'   => 'Ver detalhes',
        'href'  => '#',
    ],
    [
        'icon'  => 'doc',
        'title' => 'Fato 02',
        'desc'  => 'Projeto X foi desenvolvido pela Secretaria.',
        'cta'   => 'Ver documento',
        'href'  => '#',
    ],
    [
        'icon'  => 'lapis',
        'title' => 'Fato 03',
        'desc'  => 'Projeto X foi entregue em XX/XX.',
        'cta'   => 'Ver entrega',
        'href'  => '#',
    ],
];

$redes = [
    ['label' => 'Instagram', 'href' => '#'],
    ['label' => 'TikTok', 'href' => '#'],
    ['label' => 'Facebook', 'href' => '#'],
    ['label' => 'LinkedIn', 'href' => '#'],
];

$videos = [
    ['src' => '', 'poster' => ''],
    ['src' => '', 'poster' => ''],
    ['src' => '', 'poster' => ''],
    ['src' => '', 'poster' => ''],
    ['src' => '', 'poster' => ''],
];
?>