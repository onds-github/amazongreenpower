$(document).ready(function () {
    
    var $cep = $('.on-viacep-zipcode'),
        $place = $('.on-viacep-public-place'),
        $district = $('.on-viacep-district'),
        $city = $('.on-viacep-city'),
        $state = $('.on-viacep-state');

    function clear_address() {
        // Limpa valores do formulário de cep.
        $place.val('');
        $district.val('');
        $city.val('');
        $state.val('');
    }

    //Quando o campo cep perde o foco.
    $cep.blur(function () {
        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {
            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;
            //Valida o formato do CEP.
            if (validacep.test(cep)) {
                //Preenche os campos com "..." enquanto consulta webservice.
                $place.val('...');
                $district.val('...');
                $city.val('...');
                $state.val('...');

                //Consulta o webservice viacep.com.br/
                $.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $place.val(dados.logradouro);
                        $district.val(dados.bairro);
                        $city.val(dados.localidade);
                        $state.val(dados.uf);
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        clear_address();
                        $('body').notify({type: 'error', content: 'CEP não encontrado'});
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                clear_address();
                $('body').notify({type: 'error', content: 'Formato de CEP inválido.'});
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            clear_address();
        }
    });
});