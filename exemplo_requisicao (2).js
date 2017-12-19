// JavaScript Document

var url = "http://spcultura.prefeitura.sp.gov.br/";

// retornando todos os eventos de cinema e teatro entre o dia 01/06/2015 e 07/06/2015 de todos os subespaços do CCSP
// o resultado da busca não inclui os eventos do espaço pai, CCSP, pois o método getChildrenIds não retorna o próprio id
$.getJSON(
  url + 'api/space/getChildrenIds/169', // 169 é o id do CCSP no spcultura
  function (childrenIds){
    // para incluir o CCSP descomentar a linha abaixo
    // childrenIds.push(169);
    $.getJSON(
      url + 'api/event/findByLocation',
      {
        'space':          'IN(' + childrenIds.join(',') + ')',
        'term:linguagem': 'IN(Cinema,Teatro)',
        '@from':          '2015-06-01',
        '@to':            '2015-06-31',
        '@select':        'id,singleUrl,name,type,shortDescription,terms,classificacaoEtaria,project.name,project.singleUrl,occurrences',
        '@files':         '(avatar.avatarMedium):url',
        '@order':         'name ASC',
//        '@page': 1,    // para paginação 
//        @limit:10,     // para paginação
      },
      function (response){ console.log(response); });
  });
