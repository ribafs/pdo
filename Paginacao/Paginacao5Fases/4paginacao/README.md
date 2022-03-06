# Controle de Estoque Simplificado em PHP do "zero"

Partindo de um pequeno script de paginação que usa o plugin do jQuery bootpag.

## Nesta fase

Estarei gerando um script .sql com dados faker contendo 1000 registros para melhor testar e visualizar o comportamento do aplicativo. Farei isso usando as customizações qie fiz nas bibliotecas Faker e Faker Restaurante, encontradas em:

https://github.com/ribafs/faker-br

Para adaptar e mostrar melhor os mil registros alterarei também:

- Em db_connect.php: $row_limit = 10;
- No index.php:         maxVisible: 18,

Estes valores são relativos à altura e largura do monitor/tela, respectivamente.

Também alterei o título do aplicativo para 'Estoque Simplificado em PHP', tanto no title quanto no body. Mais a frente estarei criando variáveis para estas regiões para agilizar futuras alterações e estas ficarão num arquivo de include chamado header.php.

Com este plugin já cheguei a gerar um milhão de registros e ele suportou bem. Mas em meus testes com o Datatables, o mesmo não suporta bem.

## Na fase anterior

Estarei criando os arquivos header.php e footer.php que serão incluídos em todas as páginas tipo view, onde eles aparecem.

Importante em termos de reutilização de código, pois ao invés de repetir o código do cabeçalho e rodapé nas páginas, estaremos apenas incluindo. Isto facilita e muito a manutenção do aplicativo. Caso encessitemos alterar o código nestas regiões alteraremos apenas uma vez no arquivo (header ou footer) e ele será atualizado automaticamente em todas as páginas em que são incluídos.

## Na fase anterior

Agora estarei apenas organizando melhor as pastas:

- Criarei uma pasta chamada 'assets' e moverei para ela as pastas css, fonts e js

Na próxima fase criarei um CRUD

## Na fase anterior

Melhorei a paginação, seguindo um exemplo do site oficial do bootpag (Full example com todos os recursos dispíveis. Troquei as setas pelas palavras: Primeira e Última)

https://botmonster.com/jquery-bootpag/

Aproveitei e criei um fork do mesmo, para apoiá-lo e para manter uma cópia do mesmo por perto.

Bons recursos

Parameters $(element).bootpag({...})

    - total number of pages
    - maxVisible maximum number of visible pages
    - page page to show on start
    - leaps next/prev buttons move over one page or maximum visible pages
    - next text (default »)
    - prev text (default «)
    - href template for pagination links (default javascript:void(0);)
    - hrefVariable variable name in href template for page number (default {{number}})
    - firstLastUse do we ant first and last (default true<)/li>
    - first name of first (default 'FIRST')
    - last name of last (default 'LAST')
    - wrapClass css class for wrap (default 'pagination')
    - activeClass css class for active (default 'active')
    - disabledClass css class for disabled (default 'disabled')
    - nextClass css class for next (default 'next')
    - prevClass css class for prev (default 'prev')
    - lastClass css class for last (default 'last')
    - firstClass css class for first (default 'first')

Events available on bootpag object

    - page on page click
    - callback params:
         - event object
         - num page number clicked


## Na fase anterior

Apenas criei a paginação partindo do tutorial original do kodingmadesimple

Crédito pela paginação
https://www.kodingmadesimple.com/2017/01/simple-ajax-pagination-in-jquery-php-pdo-mysql.html

