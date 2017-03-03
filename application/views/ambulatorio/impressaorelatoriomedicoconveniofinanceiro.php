<div class="content"> <!-- Inicio da DIV content -->

    <meta http-equiv="content-type" content="text/html;charset=utf-8" />

    <? if (count($empresa) > 0) { ?>
        <h4><?= $empresa[0]->razao_social; ?></h4>
    <? } else { ?>
        <h4>TODAS AS CLINICAS</h4>
    <? } ?>
    <h4>Medico Convenios</h4>
    <h4>PERIODO: <?= substr($txtdata_inicio, 8, 2) . "/" . substr($txtdata_inicio, 5, 2) . "/" . substr($txtdata_inicio, 0, 4); ?> ate <?= substr($txtdata_fim, 8, 2) . "/" . substr($txtdata_fim, 5, 2) . "/" . substr($txtdata_fim, 0, 4); ?></h4>
    <? if ($medico == 0) { ?>
        <h4>Medico: TODOS</h4>
    <? } else { ?>
        <h4>Medico: <?= utf8_decode($medico[0]->operador); ?></h4>
    <? } ?>

    <hr>
    <? if ($contador > 0 || count($relatoriocirurgico) > 0) {
        ?>

        <? if (count($relatorio) > 0): ?>
            <table border="1">
                <thead>
                    <tr>
                        <td colspan="50"><center>PRODUÇÃO AMBULATORIAL</center></td>
                </tr>
                <tr>


                    <th class="tabela_header"><font size="-1">Convenio</th>
                    <th class="tabela_header"><font size="-1">Nome</th>
                    <th class="tabela_header"><font size="-1">Medico</th>
                    <th class="tabela_header"><font size="-1">Data</th>
                    <th class="tabela_header"><font size="-1">Qtde</th>
                    <th class="tabela_header" width="220px;"><font size="-1">Procedimento</th>
                    <? if ($clinica == 'SIM') { ?>
                        <th class="tabela_header" ><font size="-1">Valor</th>
                    <? } ?>
                    <th class="tabela_header" width="80px;"><font size="-1">Indice/Valor</th>
                    <th class="tabela_header" width="80px;"><font size="-1">Valor Medico</th>

                    <? if ($solicitante == 'SIM') { ?>
                        <th class="tabela_header" width="80px;"><font size="-1">Solicitante</th>
                    <? } ?>
                </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    $valor = 0;
                    $valortotal = 0;
                    $convenio = "";
                    $y = 0;
                    $qtde = 0;
                    $qtdetotal = 0;
                    $resultado = 0;
                    $simbolopercebtual = " %";
                    $iss = 0;
                    $perc = 0;
                    $totalperc = 0;
                    $totalgeral = 0;
                    $totalconsulta = 0;
                    $totalretorno = 0;
                    foreach ($relatorio as $item) :
                        $i++;
                        $procedimentopercentual = $item->procedimento_convenio_id;
//            $medicopercentual = $item->medico_parecer1;
                        $medicopercentual = $item->operador_id;
                        if ($item->classificacao == 1) {
                            $totalconsulta++;
                        }
                        if ($item->classificacao == 2) {
                            $totalretorno++;
                        }
                        ?>
                        <tr>
                            <td><font size="-2"><?= $item->convenio; ?></td>
                            <td><font size="-2"><?= $item->paciente; ?></td>
                            <td><font size="-2"><?= $item->medico; ?></td>
                            <td><font size="-2"><?= substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4); ?></td>
                            <td ><font size="-2"><?= $item->quantidade; ?></td>
                            <td><font size="-2"><?= utf8_decode($item->procedimento); ?></td>
                            <? if ($clinica == 'SIM') { ?>
                                <td style='text-align: right;'><font size="-2"><?= number_format($item->valor_total, 2, ",", "."); ?></td>
                            <? } ?>
                            <?
                            if ($item->percentual_medico == "t") {
                                $simbolopercebtual = " %";

                                $valorpercentualmedico = $item->valor_medico;

                                $perc = $item->valor_total * ($valorpercentualmedico / 100);
                                $totalperc = $totalperc + $perc;
                                $totalgeral = $totalgeral + $item->valor_total;
                            } else {
                                $simbolopercebtual = "";
                                $valorpercentualmedico = $item->valor_medico;

                                $perc = $valorpercentualmedico;
                                $totalperc = $totalperc + $perc;
                                $totalgeral = $totalgeral + $item->valor_total;
                            }
                            ?>
                            <td style='text-align: right;'><font size="-2"><?= $valorpercentualmedico . $simbolopercebtual ?></td>
                            <td style='text-align: right;'><font size="-2"><?= number_format($perc, 2, ",", "."); ?></td>

                            <? if ($solicitante == 'SIM') { ?>
                                <td style='text-align: right;'><font size="-2"><?= $item->medicosolicitante; ?></td>
                            <? } ?>
                        </tr>


                        <?php
                        $qtdetotal = $qtdetotal + $item->quantidade;
                    endforeach;
                    $resultadototalgeral = $totalgeral - $totalperc;
                    ?>
                    <tr>
                        <td ><font size="-1">TOTAL</td>
                        <td style='text-align: right;'><font size="-1">Nr. Procedimentos: <?= $qtdetotal; ?></td>
                        <? if ($clinica == 'SIM') { ?>
                            <td colspan="5" style='text-align: right;'><font size="-1">TOTAL CLINICA: <?= number_format($resultadototalgeral, 2, ",", "."); ?></td>
                        <? } else { ?>
                            <td colspan="4" style='text-align: right;'><font size="-1">&nbsp;</td>
                        <? } ?>
                        <td colspan="2" style='text-align: right;'><font size="-1">TOTAL MEDICO: <?= number_format($totalperc, 2, ",", "."); ?></td>
                    </tr>
                </tbody>
            </table>
        <? endif; ?>

        <? if (count($relatoriocirurgico) > 0):
            $totalprocedimentoscirurgicos = 0;
            ?>
            <br>
            <table border="1">
                <thead>
                    <tr>
                        <td colspan="50"><center>PRODUÇÃO CIRURGICA</center></td>
                </tr>
                <tr>
                    <th class="tabela_header"><font size="-1"><center>Convenio</center></th>
                <th class="tabela_header"><font size="-1"><center>Nome</center></th>
                <th class="tabela_header"><font size="-1"><center>Medico</center></th>
                <th class="tabela_header"><font size="-1"><center>Data</center></th>
                <th class="tabela_header"><font size="-1"><center>Procedimento</center></th>
                <th class="tabela_header"><font size="-1"><center>Valor Procedimento</center></th>
                <th class="tabela_header"><font size="-1"><center>Grau de Participação</center></th>
                <th class="tabela_header"><font size="-1"><center>Valor Médico</center></th>
                </tr>
                </thead>
                <tbody>
                    <?
                    $totalMedicoCirurgico = 0;
                    foreach ($relatoriocirurgico as $itens) :
                        $totalprocedimentoscirurgicos++;
                        $totalMedicoCirurgico += (float) $itens->valor_medico;
                        $totalperc += $totalMedicoCirurgico;
                        ?>

                        <tr>
                            <!--<td><font size="-2"><?= $itens->guia_id; ?></td>-->
                            <td ><font size="-2"><?= $itens->convenio; ?></td>
                            <td><font size="-2"><?= $itens->paciente; ?></td>
                            <td><font size="-2"><?= $itens->medico; ?></td>
                            <td><font size="-2"><?= date("d/m/Y", strtotime($itens->data)); ?></td>
                            <td ><font size="-2"><?= $itens->procedimento; ?></td>
                            <td style='text-align: right;'><font size="-2"><?= number_format($itens->valor_total, 2, ",", "."); ?></td>
                            <td><font size="-2"><?= $itens->funcao; ?></td>
                            <td style='text-align: right;'><font size="-2"><?= number_format($itens->valor_medico, 2, ",", "."); ?></td>
                        </tr>

        <? endforeach; ?>
                    <tr>
                        <td ><font size="-1">TOTAL</td>
                        <td style='text-align: right;'><font size="-1">Nr. Procedimentos: <?= $totalprocedimentoscirurgicos; ?></td>
                        <td colspan="4" style='text-align: right;'><font size="-1">&nbsp;</td>
                        <td colspan="2" style='text-align: right;'><font size="-1">TOTAL MEDICO: <?= number_format($totalMedicoCirurgico, 2, ",", "."); ?></td>
                    </tr>

                </tbody>
            </table>
        <?
        endif;

        if ($medico != 0) {
            $resultado = $totalperc;
            if ($totalretorno > 0 || $totalconsulta > 0) :
                ?>
                <hr>
                <table border="1">
                    <tr>
                        <th colspan="2" width="200px;">RESUMO</th>
                    </tr>
                    <tr>
                        <td>TOTAL CONSULTAS</td>
                        <td style='text-align: right;' width="30px;"><?= $totalconsulta; ?></td>
                    </tr>

                    <tr>
                        <td>TOTAL RETORNO</td>
                        <td style='text-align: right;'><?= $totalretorno; ?></td>
                    </tr>
        <? endif;
        if ($totalprocedimentoscirurgicos > 0):
            ?>
                    <tr>
                        <td>TOTAL PROC. CIRURGICOS</td>
                        <td style='text-align: right;'><?= $totalprocedimentoscirurgicos; ?></td>
                    </tr>
            <? endif; ?>
            </table>
            <?
            $irpf = 0;
            if ($totalperc >= $medico[0]->valor_base) {
                $irpf = $totalperc * ($medico[0]->ir / 100);
                ?>
                <hr>
                <table border="1">
                    <tr>
                        <th colspan="2" width="200px;">RESUMO FISCAL</th>
                    </tr>
                    <tr>
                        <td>TOTAL</td>
                        <td style='text-align: right;'><?= number_format($totalperc, 2, ",", "."); ?></td>
                    </tr>

                    <tr>
                        <td>IRPF</td>
                        <td style='text-align: right;'><?= number_format($irpf, 2, ",", "."); ?></td>
                    </tr>
                    <?
                    $resultado = $totalperc - $irpf;
                } else {
                    ?>
                    <hr>
                    <table border="1">
                        <tr>
                            <th colspan="2" width="200px;">RESUMO FISCAL</th>
                        </tr>
                        <?
                    }
                    if ($totalperc > 215) {
                        $pis = $totalperc * ($medico[0]->pis / 100);
                        $csll = $totalperc * ($medico[0]->csll / 100);
                        $cofins = $totalperc * ($medico[0]->cofins / 100);
                        $resultado = $resultado - $pis - $csll - $cofins;
                        ?>
                        <tr>
                            <td>PIS</td>
                            <td style='text-align: right;'><?= number_format($pis, 2, ",", "."); ?></td>
                        </tr>
                        <tr>
                            <td>CSLL</td>
                            <td style='text-align: right;'><?= number_format($csll, 2, ",", "."); ?></td>
                        </tr>
                        <tr>
                            <td>COFINS</td>
                            <td style='text-align: right;'><?= number_format($cofins, 2, ",", "."); ?></td>
                        </tr>
                        <?
                        $iss = $totalperc * ($medico[0]->iss / 100);
                        $resultado = $resultado - $iss;
                    }
                    if ($iss > 0) {
                        ?>
                        <tr>
                            <td>ISS</td>
                            <td style='text-align: right;'><?= number_format($iss, 2, ",", "."); ?></td>
                        </tr>
        <? } ?>
                    <tr>
                        <td>RESULTADO</td>
                        <td style='text-align: right;'><?= number_format($resultado, 2, ",", "."); ?></td>
                    </tr>
                </table>
                <? ?>
        <? if ($medico != 0) {
            ?>

                    <form name="form_caixa" id="form_caixa" action="<?= base_url() ?>ambulatorio/guia/fecharmedico" method="post">
                        <input type="hidden" class="texto3" name="tipo" value="<?= $medico[0]->tipo_id; ?>" readonly/>
                        <input type="hidden" class="texto3" name="nome" value="<?= $medico[0]->credor_devedor_id; ?>" readonly/>
                        <input type="hidden" class="texto3" name="conta" value="<?= $medico[0]->conta_id; ?>" readonly/>
                        <input type="hidden" class="texto3" name="classe" value="<?= $medico[0]->classe; ?>" readonly/>
                        <input type="hidden" class="texto3" name="observacao" value="<?= "Período " . $txtdata_inicio . " até " . $txtdata_fim . " médico: " . $medico[0]->operador; ?>" readonly/>
                        <input type="hidden" class="texto3" name="valor" value="<?= $resultado; ?>" readonly/>
                        <button type="submit" name="btnEnviar">Producao medica</button>
                    </form>
                    <?
                }
            }
            ?>

            <div>
                <div style="display: inline-block">
                    <table border="1">
                        <thead>
                            <tr>
                                <td colspan="50"><center>PRODUÇÃO AMBULATORIAL</center></td>
                        </tr>
                        <tr>
                            <th class="tabela_header"><font size="-1">Medico</th>
                            <th class="tabela_header"><font size="-1">Qtde</th>
                            <th class="tabela_header"><font size="-1">Produ&ccedil;&atilde;o Medico</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?
                            foreach ($relatoriogeral as $itens) :
                                ?>

                                <tr>
                                    <td><font size="-2"><?= $itens->medico; ?></td>
                                    <td ><font size="-2"><?= $itens->quantidade; ?></td>
                                    <td ><font size="-2"><?= number_format($itens->valor, 2, ",", "."); ?></td>
                                </tr>

    <? endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div style="display: inline-block">
                    <table border="1">
                        <thead>
                            <tr>
                                <td colspan="50"><center>PRODUÇÃO CIRURGICA</center></td>
                        </tr>
                        <tr>
                            <th class="tabela_header"><font size="-1">Medico</th>
                            <th class="tabela_header"><font size="-1">Qtde</th>
                            <th class="tabela_header"><font size="-1">Produ&ccedil;&atilde;o Medico</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?
                            foreach ($relatoriogeral as $itens) :
                                ?>

                                <tr>
                                    <td><font size="-2"><?= $itens->medico; ?></td>
                                    <td ><font size="-2"><?= $itens->quantidade; ?></td>
                                    <td ><font size="-2"><?= number_format($itens->valor, 2, ",", "."); ?></td>
                                </tr>

    <? endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr>

            <?
        }
        else {
            ?>
            <h4>N&atilde;o h&aacute; resultados para esta consulta.</h4>
            <?
        }
        ?>

</div> <!-- Final da DIV content -->
<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript">



    $(function () {
        $("#accordion").accordion();
    });

</script>
