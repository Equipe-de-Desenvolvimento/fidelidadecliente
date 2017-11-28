<?
//Da erro no home

if ($this->session->userdata('autenticado') != true) {
    redirect(base_url() . "login/index/login004", "refresh");
}
$perfil_id = $this->session->userdata('perfil_id');
$operador_id = $this->session->userdata('operador_id');
$internacao = $this->session->userdata('internacao');

function alerta($valor) {
    echo "<script>alert('$valor');</script>";
}

function debug($object) {
    
}
?>
<!DOCTYPE html PUBLIC "-//carreW3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="pt-BR" >
    <head>
        <title>STG - SISTEMA DE GESTAO DE CLINICAS v1.0</title>
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <!-- Reset de CSS para garantir o funcionamento do layout em todos os brownsers -->
        <link href="<?= base_url() ?>css/reset.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url() ?>css/estilo.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>css/batepapo.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>css/jquery-treeview.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-cookie.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-treeview.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery.bestupper.min.js"  ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/scripts.js" ></script>
        <!--<script type="text/javascript" src="<?= base_url() ?>js/jquery.js" ></script>-->
        <script type="text/javascript">
//            var jQuery = jQuery.noConflict();
            var chatsAbertos = new Array();

            (function ($) {
                $(function () {
                    $('input:text').setMask();
                });
            })(jQuery);


            function mensagensnaolidas() {
                jQuery.ajax({
                    type: "GET",
                    url: "<?= base_url(); ?>" + "batepapo/totalmensagensnaolidas",
                    dataType: "json",
                    success: function (retorno) {
                        if (jQuery(".batepapo_div #contatos_chat_lista span").length > 0) {
                            jQuery(".batepapo_div #contatos_chat_lista span").remove();
                        }

                        if (retorno != 0) {
                            jQuery(".batepapo_div #contatos_chat_lista").append("<span class='total_mensagens'></span>");
                            jQuery(".batepapo_div .total_mensagens").text("+" + retorno);
                        }
                    }
                });
            }
            mensagensnaolidas();

            function carregacontatos() {
                jQuery.ajax({
                    type: "GET",
                    url: "<?= base_url(); ?>" + "batepapo/pesquisar",
                    dataType: "json",
                    success: function (retorno) {
                        jQuery.each(retorno, function (i, usr) {
//                            console.log(usr);
                            var tags = null;
                            if (usr.operador_id != <? echo $operador_id ?> && usr.usuario != 0) {
                                tags = "<li id='" + usr.operador_id + "'><div class='imgPerfil'></div>";
                                tags += "<a href='#' id='<? echo $operador_id ?>:" + usr.operador_id + "' class='comecarChat'>" + usr.usuario + "</a>";
                                if (usr.num_mensagens != 0) {
                                    tags += "<span class='total_mensagens'> +" + usr.num_mensagens + " </span>";
                                }
                                tags += "<span id='usr.operador_id'></span></li>";
                                jQuery("#principalChat #usuarios_online ul").append(tags);
                            }
                        });
                    }
                });
            }

            //abri uma nova janela
            function adicionarJanela(id, nome, status) {

                //atribui dinamicamente a posicao da janela na pagina
                var numeroJanelas = Number(jQuery("#chats .janela_chat").length);
                if (numeroJanelas < 3) {
                    var posicaoJanela = (270 + 15) * numeroJanelas;
                    var estiloJanela = 'float:none; position: absolute; bottom:0; right:' + posicaoJanela + 'px';

                    //pega o id do operador origem e do operador destino
                    var splitOperadores = id.split(':');
                    var operadorDestino = Number(splitOperadores[1]);

                    //criando a janela de mensagem
                    var janela;
                    janela = "<div class='janela_chat' id='janela_" + operadorDestino + "' style='" + estiloJanela + "'>";
                    janela += "<div class='cabecalho_janela_chat'> <a href='#' class='fechar'>X</a>";
                    janela += "<span class='nome_chat'>" + nome + "</span><span id='" + operadorDestino + "'></span></div>";
                    janela += "<div class='corpo_janela_chat'><div class='mensagens_chat'><ul></ul></div>";
                    janela += "<div class='enviar_mensagens_chat' id='" + id + "'>";
                    janela += "<input type='text' maxlength='300' name='mensagem_chat' class='mensagem_chat' id='" + id + "' /></div></div></div>";

                    //acrescenta a janela ao aside chats
                    jQuery("#chats").append(janela);
                    chatsAbertos.push(operadorDestino);
                } else {
                    alert("Voce estorou o limite de janelas.")
                }
            }

            //retornando historico de conversas
            function retorna_historico(idJanela) {
                var operadorOrigem = <? echo $operador_id; ?>;
                jQuery.ajax({
                    type: "GET",
                    url: "<?= base_url(); ?>" + "batepapo/historicomensagens",
                    data: "operador_origem=" + operadorOrigem + "&operador_destino=" + idJanela,
                    dataType: 'json',
                    success: function (retorno) {
                        jQuery.each(retorno, function (i, msg) {
                            if (jQuery('#janela_' + msg.janela).length > 0) {

                                if (msg.mensagem.length > 26) {
                                    var texto = "";
                                    var inicio = 0;
                                    var fim = 25;
                                    var br = "<br>";

                                    for (var n = 0; n < msg.mensagem.length; n++) {
                                        if (n == fim) {
                                            texto += msg.mensagem.substring(inicio, fim);
                                            texto += br;
                                            inicio = fim;
                                            fim += 25;
                                        }
                                    }

                                    msg.mensagem = texto;
                                }

                                if (operadorOrigem == msg.id_origem) {
                                    jQuery("#janela_" + msg.janela + " .corpo_janela_chat .mensagens_chat ul").append("<li class='eu' id='" + msg.chat_id + "'><p>" + msg.mensagem + "</p></li>");
                                } else {
                                    jQuery("#janela_" + msg.janela + " .corpo_janela_chat .mensagens_chat ul").append("<li id='" + msg.chat_id + "'><p>" + msg.mensagem + "</p></li>");
                                }
                            }
                        });
                        var altura = jQuery("#janela_" + idJanela + " .corpo_janela_chat .mensagens_chat").height();
                        jQuery("#janela_" + idJanela + " .corpo_janela_chat .mensagens_chat").animate({scrollTop: 1000000}, '500');
                    }
                });
            }


        </script>

    </head>
    <script type="text/javascript" src="<?= base_url() ?>js/funcoes.js"></script>

    <?php
    $this->load->library('utilitario');
    $paciente_id = $this->session->userdata('paciente_id');
    Utilitario::pmf_mensagem($this->session->flashdata('message'));
    ?>


    <div class="container">
        <div class="header">
            <div id="imglogo">
                <img src="<?= base_url(); ?>img/stg - logo.jpg" alt="Logo"
                     title="Logo" height="70" id="Insert_logo"
                     style="display:block;" />
            </div>
            <div id="login">
                <div id="user_info">
                    <label style='font-family: serif; font-size: 8pt;'> </label>
                    <label style='font-family: serif; font-size: 8pt;'></label>
                </div>
                <div id="login_controles">
                    <!--
                    <a href="#" alt="Alterar senha" id="login_pass">Alterar Senha</a>
                    -->
                    <a id="login_sair" title="Sair do Sistema" onclick="javascript: return confirm('Deseja realmente sair da aplicação?');"
                       href="<?= base_url() ?>login/sair">Sair</a>

                    <div class="batepapo_div">
                        <a id="contatos_chat_lista" href="#">
                            <img src="<?= base_url(); ?>img/chat_icon.png" alt="Batepapo"
                                 title="Batepapo"/></a>
                    </div>
                </div>
                <!--<div id="user_foto">Imagem</div>-->

            </div>
        </div>
        <div class="decoration_header">&nbsp;</div>

        <!-- INICIO BATEPAPO -->
        <div id="principalChat">
            <aside id="usuarios_online" >
                <ul>
                </ul>
            </aside>

            <aside id="chats">

            </aside>


        </div>
        <!-- FIM BATEPAPO -->

        <!-- Fim do Cabeçalho -->
        <div class="barraMenus" style="float: left;">
            <ul id="menu" class="filetree">
                <li><span class="folder">Clientes</span>
                    <ul>
                        <!--<li><span class="folder">Cadastro</span>-->
                            <!--<ul>-->
                              
                                    <!--<li><span class="file"><a href="<?= base_url() ?>cadastros/pacientes/novo">Novo</a></span></li>-->
                                    <li><span class="file"><a href="<?= base_url() ?>cadastros/pacientes/carregar/<?=$paciente_id ?>"> Editar Cadastro</a></span></li>
                                    <!--<li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatorioinadimplentes">Relatorio Inadimplentes</a></span></li>-->
                                    <!--<li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriocadastro">Relatorio Cadastro</a></span></li>-->
                                    <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exame/listaragendamentoweb/<?=$paciente_id ?>" target="_blank">Agendamento </a></span></li>
                                    <!--<li><span class="file"><a href="<?= base_url() ?>ambulatorio/indicacao">Relatorio</a></span></li>-->
                               
                            <!--</ul>-->
                        <!--</li>-->
                        
                    </ul>
                </li>

          

                
                <li><span class="file"><a onclick="javascript: return confirm('Deseja realmente sair da aplicação?');"
                                          href="<?= base_url() ?>login/sair">Sair</a></span>
                    
                </li>
            </ul>                       
            <!-- Fim da Barra Lateral -->
        </div>
        <div class="mensagem"><?
                        if (isset($mensagem)): echo $mensagem;
                        endif;
                        ?></div>
        <script type="text/javascript">
            $("#menu").treeview({
                animated: "normal",
                persist: "cookie",
                collapsed: true,
                unique: true
            });

            jQuery("#contatos_chat_lista").on("click", function () {
                //mostrando a lista de contatos
                carregacontatos();

                //abrindo a janelas de batepapo
                jQuery("#principalChat").on("click", "#usuarios_online a", function () {
                    var id = jQuery(this).attr("id");
                    jQuery(this).removeClass("comecarChat");

                    var status = jQuery(this).next().attr("class");
                    var splitId = id.split(":");
                    var idJanela = Number(splitId[1]);

                    if (jQuery("#janela_" + idJanela).length == 0) {
                        var nome = jQuery(this).text();
                        adicionarJanela(id, nome, status);
                        retorna_historico(idJanela);
                    } else {
                        jQuery(this).removeClass("comecarChat");
                    }
                });

                jQuery("#principalChat #usuarios_online").on("mouseleave", function () {
                    $("#principalChat #usuarios_online ul li").remove();
                });
            });


            //minimizando as janelas
            jQuery("#principalChat").on('click', '.cabecalho_janela_chat', function () {
                var corpo_janela_chat = jQuery(this).next();
                corpo_janela_chat.toggle(100);
            });


            //fechando a janela
            jQuery("#principalChat").on('click', '.fechar', function () {

                var janelaSelecionada = jQuery(this).parent().parent();
                var idJanela = janelaSelecionada.attr("id");
                var janelaSplit = idJanela.split("_");
                var janelaFechada = Number(janelaSplit[1]);

                var janelasAbertas = Number(jQuery(".janela_chat").length) - 1;
                var indice = Number(jQuery(".fechar").index(this));
                var janelasAfrente = janelasAbertas - indice;

                for (var i = 1; i <= janelasAfrente; i++) {
                    jQuery(".janela_chat:eq(" + (indice + i) + ")").animate({right: "-=285"}, 200);
                }

                janelaSelecionada.remove();
                jQuery("#usuarios_online li#" + janelaFechada + " a").addClass("comecar");

                var test;
                for (var i = 0; i < chatsAbertos.length; i++) {
                    test = Number(chatsAbertos[i]);
                    if (janelaFechada == test) {
                        chatsAbertos.splice(i, 1);
                        break;
                    }
                }
            });

            //Enviando mensagens
            jQuery("#principalChat").on('keyup', '.mensagem_chat', function (tecla) {

                if (tecla.which == 13) {
                    var texto = jQuery(this).val();
                    var len = Number(texto.length);

                    if (len > 0) {
                        var id = jQuery(this).attr("id");
                        var splitId = id.split(":");
                        var operadorOrigem = Number(splitId[0]);
                        var operadorDestino = Number(splitId[1]);
                        jQuery.ajax({
                            type: "GET",
                            url: "<?= base_url(); ?>" + "batepapo/enviarmensagem",
                            data: "mensagem=" + texto + "&origem=" + operadorOrigem + "&destino=" + operadorDestino,
                            success: function () {
                                jQuery('.mensagem_chat').val('');
                                verifica(0, 0, <? echo $operador_id ?>);
                            }
                        });
                    }

                }
            });

            //Atualizando novas mensagens (LongPolling)
            function verifica(timestamp, ultimoId, operadorOrigem) {
                var t;

                jQuery.ajax({
                    url: "<?= base_url(); ?>" + "batepapo/atualizamensagens",
                    type: "GET",
                    data: 'timestamp=' + timestamp + '&ultimoid=' + ultimoId + '&usuario=' + operadorOrigem,
                    dataType: 'json',
                    success: function (retorno) {
                        clearInterval(t);

                        if (retorno.status == 'resultados' || retorno.status == 'vazio') {

//                                //funcao chamando a si mesma a cada 1s
//                                t = setTimeout(function () {
//                                    verifica(retorno.timestamp, retorno.ultimoId, retorno.operadorOrigem);
//                                }, 2000);


                            //verifica se ha mensagens novas
                            if (retorno.status == 'resultados') {
                                jQuery.each(retorno.dados, function (i, msg) {

                                    //testando se ela ja esta aberta
                                    if (jQuery("#janela_" + msg.janela).length > 0) {

                                        if (jQuery("#janela_" + msg.janela + " .mensagens_chat ul li#" + msg.chat_id).length == 0 && msg.janela > 0) {

                                            if (msg.mensagem.length > 26) {
                                                var texto = "";
                                                var inicio = 0;
                                                var fim = 25;
                                                var br = "<br>";

                                                for (var n = 0; n < msg.mensagem.length; n++) {
                                                    if (n == fim) {
                                                        texto += msg.mensagem.substring(inicio, fim);
                                                        texto += br;
                                                        inicio = fim;
                                                        fim += 25;
                                                    }
                                                }

                                                msg.mensagem = texto;
                                            }

                                            if (operadorOrigem == msg.id_origem) {
                                                jQuery("#janela_" + msg.janela + " .mensagens_chat ul").append("<li class='eu' id='" + msg.chat_id + "'><p>" + msg.mensagem + "</p></li>");
                                            } else {
                                                jQuery("#janela_" + msg.janela + " .mensagens_chat ul").append("<li id='" + msg.chat_id + "'><div class='imgPerfil'></div><p>" + msg.mensagem + "</p></li>");
                                            }
                                        }

                                        jQuery.ajax({
                                            url: "<?= base_url(); ?>" + "batepapo/visualizacontatoaberto",
                                            type: "GET",
                                            data: 'operador_destino=' + msg.janela,
                                            success: function () {

                                            }
                                        });
                                    }
                                });

                                var altura = jQuery(".corpo_janela_chat .mensagens_chat").height();
                                jQuery(".corpo_janela_chat .mensagens_chat").animate({scrollTop: 1000000}, '500');
                            }
                        }
                    },
                    error: function (retorno) {
                        clearInterval(t);
                        t = setTimeout(function () {
                            verifica(retorno.timestamp, retorno.ultimoId, retorno.operadorOrigem);
                        }, 10000);
                    }
                });
            }

            function buscamensagens() {
                setInterval(function () {
                    verifica(0, 0,<? echo $operador_id ?>);
                    mensagensnaolidas();
                }, 3000);
            }

            buscamensagens();

        </script>
