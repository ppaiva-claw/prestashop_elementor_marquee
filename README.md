# Escadote Announcement Bar Widget (PrestaShop + Creative Elements)

O **Escadote Widget** adiciona um widget de **barra de anúncios em formato marquee** ao Creative Elements no PrestaShop.

Com este widget, pode criar uma faixa horizontal com mensagens e ícones em movimento contínuo, ideal para comunicar promoções, portes grátis, prazos de entrega e avisos importantes na loja.

## Funcionalidades

- Categoria própria no Creative Elements: **Escadote**.
- Widget **Escadote Announcement Bar**.
- Lista de itens configurável (texto + ícone) através de repeater.
- Direção da animação:
  - direita → esquerda
  - esquerda → direita
- Controlo de velocidade da animação.
- Opção para pausar ao passar o rato.
- Personalização de estilo:
  - cor de fundo
  - cor do texto
  - tipografia
  - tamanho dos ícones
  - espaçamento entre itens

## Requisitos

- PrestaShop **1.7.8.0** ou superior.
- Módulo **Creative Elements** instalado e ativo.
- PHP compatível com a versão usada pela sua instalação PrestaShop.

## Instalação

### Opção A — Backoffice (recomendado)

1. Comprima a pasta do módulo em `.zip` (a pasta raiz deve chamar-se `escadote_widget`).
2. No PrestaShop, aceda a **Módulos > Gestor de Módulos**.
3. Clique em **Carregar um módulo** e selecione o ficheiro `.zip`.
4. Após upload, clique em **Instalar** no módulo **Escadote Widget**.

### Opção B — Instalação manual por ficheiros

1. Copie a pasta `escadote_widget` para:
   - `/modules/escadote_widget`
2. No Backoffice, vá a **Módulos > Gestor de Módulos**.
3. Procure por **Escadote Widget**.
4. Clique em **Instalar**.

## Como usar no Creative Elements

1. Abra uma página com o editor do Creative Elements.
2. Procure pela categoria **Escadote**.
3. Arraste o widget **Escadote Announcement Bar** para a secção pretendida.
4. Configure os itens, velocidade, direção e estilos no painel do widget.

## Notas

- O CSS do marquee é carregado no frontoffice via hook `actionFrontControllerSetMedia`.
- O registo do widget ocorre no hook `actionCreativeElementsInit`.

## Estrutura principal

- `escadote_widget/escadote_widget.php` — classe principal do módulo e hooks.
- `escadote_widget/classes/Widget/EscadoteAnnouncementBar.php` — definição do widget Creative Elements.
- `escadote_widget/views/css/announcement-bar.css` — estilos e animação marquee.
