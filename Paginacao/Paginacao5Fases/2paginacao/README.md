# Controle de Estoque Simplificado em PHP do "zero"

Partindo de um pequeno script de paginação que usa o plugin do jQuery bootpag.

## Nesta fase

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

