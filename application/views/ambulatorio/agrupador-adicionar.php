<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3 class="singular"><a href="#">Cadastro Grupo de Pagamento</a></h3>
        <div>
            <form name="form_formapagamento" id="form_formapagamento" action="<?= base_url() ?>ambulatorio/procedimentoplano/gravaragrupadoradicionar" method="post">

                <dl class="dl_desconto_lista">
                    <dt>
                        <label>Nome</label>
                    </dt>
                    <dd>
                        <input type="hidden" name="agrupador_id" class="texto10" value="<?= $agrupador[0]->agrupador_id; ?>" />
                        <input type="text" name="txtNome" class="texto05"  value="<?= $agrupador[0]->nome; ?>"/>
                    </dd>
                    
                    <dt>
                        <label>Procedimento</label>
                    </dt>
                    <dd>
                        <select name="procedimento" id="procedimento" class="texto03" required>
                            <option value="">SELECIONE</option>
                            <? foreach ($procedimentos as $value) { ?>
                                <option value="<?= $value->procedimento_convenio_id ?>"><?= $value->procedimento ?> - <?= $value->convenio ?></option>
                            <? } ?>                            
                        </select>
                    </dd>
                </dl>    
                <hr/>
                <button type="submit" name="btnEnviar">Enviar</button>
                <button type="reset" name="btnLimpar">Limpar</button>
                <button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </form>
            <br/><br/>


            <? if (count($relatorio) > 0) { ?>

                <table>
                    <thead>
                        <tr>
                            <th class="tabela_header">Nome</th>
                            <th class="tabela_header">Convenio</th>
                            <th class="tabela_header" width="70px;" colspan="2"><center>Detalhes</center></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?
                        $estilo_linha = "tabela_content01";
                        foreach ($relatorio as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->nome; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->convenio; ?></td>
                                <td class="<?php echo $estilo_linha; ?>" width="70px;">                                  
                                    <a onclick="javascript: return confirm('Deseja realmente exlcuir esse Forma?');" href="<?= base_url() ?>ambulatorio/procedimentoplano/excluirprocedimentoagrupador/<?= $item->procedimento_agrupado_id ?>/<?= $item->agrupador_id ?>">Excluir</a>
                                </td>
                            </tr>

                        </tbody>
                    <? } ?>

                    <tfoot>
                        <tr>
                            <th class="tabela_footer" colspan="6">
                                Valor Total: <?php echo number_format(count($relatorio)); ?>
                            </th>
                        </tr>
                    </tfoot>
                </table>

            <? } ?>
        </div>
    </div>
</div> <!-- Final da DIV content -->

<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript">

                                        $(function () {
                                            $('#convenio1').change(function () {
                                                if ($(this).val()) {
                                                    $('.carregando').show();
                                                    $.getJSON('<?= base_url() ?>autocomplete/procedimentoconvenio', {convenio1: $(this).val()}, function (j) {
                                                        options = '<option value=""></option>';
                                                        for (var c = 0; c < j.length; c++) {
                                                            options += '<option value="' + j[c].procedimento_convenio_id + '">' + j[c].procedimento + ' - ' + j[c].codigo + '</option>';
                                                        }
                                                        $('#procedimento1').html(options).show();
                                                        $('.carregando').hide();
                                                    });
                                                } else {
                                                    $('#procedimento1').html('<option value="">Selecione</option>');
                                                }
                                            });
                                        });
                                        
                                        $('#btnVoltar').click(function () {
                                            $(location).attr('href', '<?= base_url(); ?>cadastros/formapagamento/grupospagamento');
                                        });

                                        $(function () {
                                            $("#accordion").accordion();
                                        });

</script>

