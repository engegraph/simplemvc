<?php namespace Core\Classes\Modals;

use Core\Model;

class Form
{
    /**
     * @var Model $Model Modelo a ser renderizado no formulário
     */
    private $Model;

    /**
     * @var int $ColumnsLine quantidade máxima de colunas em uma linha
     */
    private $ColumnsLine = 3;

    /**
     * @var array $Hidden Fields que devem ser escondidos
     */
    private $Hidden = ['Id'];

    /**
     * @var array $NoAllow Fields que não serão processados
     */
    private $NoAllow = ['Id'];

    /**
     * @var array $FieldsAloneInRow Fields que devem ficar sozinhos na linha
     */
    private $FieldsAloneInRow = ['textarea', 'radio', 'checkbox'];

    /**
     * Form constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->Model = $model;
    }


    /**
     * Retorna o formulário montado
     * @return string|array
     */
    public function getMarckup()
    {
        $Form = $this->renderHtmlForm();
        $Htm = '<form class="wSGI-form-modal" method="post"><fieldset>%s</fieldset></form>';
        return sprintf($Htm, $Form);
    }


    /**
     * Monta a grid do modle no modal
     * @param string|null $Highlight
     * @return string
     */
    public function getList(string $Highlight = null) : string
    {
        $Table = '<div class="table-responsive"><table id="modal-data-grid" class="table data-table data-table-sort table-striped table-bordered table-hover">%s</table></div>';
        $modelName = $this->Model->getClass();
        $Data = $this->Model::orderBy('DataCriacao','Desc')->get();

        $Columns = [];
        foreach ($this->Model->columns as $Name => $Column)
        {
            if(!$Column['list'])
                continue;
            $Columns[] = $Column['label'];
        }
        $colspan = sizeof($Columns);

        $Th = '';
        $Class = 'cell-th';
        foreach ($Columns as $Name)
        {
            if($Name != 'Id')
                $Th .= '<th class="'.$Class.'">'.$Name.'</th>';
            else
            {
                $S = '<div class="checkbox checkbox-inline checkbox-danger"><input type="checkbox" id="uuid-toggle"><label for="uuid-toggle">#</label></div>';
                $Th .= '<th class="'.$Class.' cell-toggle-all">'.$S.'</th>';
            }
        }
        $Thead = sprintf('<thead><tr>%s</tr></thead>', $Th);

        if($Data->count())
        {

            $Tr = '';
            $customClass = '';
            $i = 1;
            foreach ($Data as $Object)
            {
                $Td = '';
                foreach ($this->Model->columns as $Property => $Column)
                {
                    $Class = 'cell-link ';

                    if(!$Column['list'])
                        continue;

                    $Value = $Object->{$Property};
                    if($Property != 'Id')
                    {
                        if(!$Column['link'])
                            $Class = '';

                        if(isset($Column['cssClass']))
                            $customClass = ' '.$Column['cssClass'];

                        $Value = $this->getValueList($Object, $Property);
                        $Td .= '<td class="'.$Class.$modelName.'-'.$Property.$customClass.'">'.$Value.'</th>';
                    }
                    else
                    {
                        $Id = 'uuid-'.$i;
                        $Uuid = str_guid($Value);
                        $Name = $modelName.'[Uuid][]';
                        #$Anchor = '<a href="javascript:void(0);" data-modal-form="'.$modelName.'" data-modal-uuid="'.str_guid($Value).'" style="display: none; visibility: hidden;"></a>';
                        $Str = '<div class="checkbox checkbox-inline checkbox-danger"><input type="checkbox" id="'.$Id.'" value="'.$Uuid.'" name="'.$Name.'"><label for="'.$Id.'">'.$i.'</label></div>';
                        $Td .= '<td class="cell-uuid">'.$Str.'</th>';
                    }
                }
                $Tr .= sprintf('<tr class="row-data'.(($Highlight != $Object->Id) ? '' : ' highlight').'">%s</tr>', $Td);

                /*if($i==10)
                    break;*/

                $i++;
            }
            $Tbody = sprintf('<tbody>%s</tbody>', $Tr);
            $Htm = sprintf($Table, $Thead.$Tbody);
        }
        else
        {
            $Tbody = '<tbody><tr><td class="text-center" colspan="'.$colspan.'">Sem Registros</td></tr></tbody>';
            $Htm = sprintf($Table, $Thead.$Tbody);
        }

        return $Htm;
    }


    /**
     * @return string
     */
    private function renderHtmlForm() : string
    {
        $Htm = '';
        foreach ($this->getRows() as $Row)
        {
            $colSize = 12/sizeof($Row);
            $StrCols = '';
            foreach ($Row as $Field)
                $StrCols .= sprintf('<section class="col-sm-%s">%s</section>', $colSize, $this->getFieldForm($Field));
            $StrRow = sprintf('<div class="row">%s</div>', $StrCols);
            $Htm .= $StrRow;
        }
        return $Htm;
    }


    private function getFieldForm(array $Field) : string
    {
        extract($Field);

        $Htm  = '<div class="form-group">%s</div>';
        $Str = '';
        if($Field['showlabel'])
            $Str  .= '<label class="control-label" for="'.$name.'">'.$label.'</label>';

        $model = $this->Model->getClass();
        $prop  = "{$model}[{$name}]";
        $Value = $this->Model->{$name};

        switch ($type)
        {
            case 'select':
                $Str .= '<select name="'.$prop.'" class="form-control select2" id="'.$name.'" placeholder="'.$label.'">';
                $Str .= '<option value=""> -- '.($Field['showlabel'] ? 'selecione' : $label).' -- </option>';
                $is_assoc = is_associative($options, true);
                foreach ($options as $Key => $Val)
                {
                    $Key = $is_assoc ? $Key : $Val;
                    $Str .= '<option value="'.$Key.'"';
                    $Str .= $Key != $Value ? '' : ' selected';
                    $Str .= '>'.$Val.'</option>';
                    #$Str .= '<option value="'.$Key.'">'.$Val.'</option>';
                }
                $Str .= '</select>';
                break;

            case 'radio':
                if(!sizeof($options))
                    $options = [1=>'Sim','0'=>'Não'];
                $is_assoc = is_associative($options, true);
                $Str .= '<br>';
                foreach ($options as $Key => $Val):
                    $id_str = $name.'_'.$Key;
                    $Key =  $is_assoc ? $Key : ($data_type=='nvarchar' ? $Val : $Key);
                    $Str .= '<div class="radio radio-inline radio-primary">';
                    $Str .= '<input type="radio" name="'.$prop.'" id="'.$id_str.'" value="'.$Key.'" class="form-control"';
                    $Str .= $Key != $Value ? '' : ' checked';
                    $Str .= '><label for="'.$id_str.'">'.$Val.'</label>';
                    $Str .= '</div>';
                endforeach;
                break;

            case 'checkbox':
                if(!sizeof($options))
                    $options = [1=>'Sim'];

                $is_assoc = is_associative($options, true);
                $Str .= '<br>';
                $prop = sizeof($options) > 1 ? "{$model}[{$name}][]" : $prop ;
                foreach ($options as $Key => $Val):
                    $id_str = $name.'_'.$Key;
                    $Key =  $is_assoc ? $Key : ($data_type=='nvarchar' ? $Val : $Key);
                    $Str .= '<div class="checkbox checkbox-inline checkbox-primary">';
                    $Str .= '<input type="checkbox" name="'.$prop.'" id="'.$id_str.'" value="'.$Key.'" class="form-control"';
                    $Str .= $Key != $Value ? '' : ' checked';
                    $Str .= '><label for="'.$id_str.'">'.$Val.'</label>';
                    $Str .= '</div>';
                endforeach;
                break;

            case 'textarea':
                $Str .= '<textarea class="form-control autosize" rows="1" name="'.$prop.'" placeholder="'.$label.'" id="'.$name.'">'.$Value.'</textarea>';
                break;

            default:
                $Str .= '<input type="'.$type.'" class="form-control" name='.$prop.' placeholder="'.$label.'" id="'.$name.'" value="'.$Value.'">';
                $Str .= '<input type="hidden" name="modelName" value="'.$model.'">';
                $Str .= '<input type="hidden" name="uuid" value="'.str_guid($this->Model->Id).'">';
        }
        return sprintf($Htm, $Str);
    }


    /**
     * Processa as colunas organizando-as em linhas
     * @return array
     */
    private function getRows() : array
    {
        $Rows = [];
        $FieldsAloneInRow = [];
        $Columns = [];
        $i = 1;
        foreach ($this->getColumns() as $Name => $Field)
        {
            $Columns[$Name] = $Field;
            if(in_array($Field['type'], $this->FieldsAloneInRow))
            {
                $FieldsAloneInRow[] = [$Name=>$Field];
                unset($Columns[$Name]);
                continue;
            }
            if($i%$this->ColumnsLine == 0)
            {
                $Rows[] = $Columns;
                $Columns = [];
            }
            $i++;
        }
        if(sizeof($FieldsAloneInRow))
            foreach ($FieldsAloneInRow as $Col)
                $Rows[] = $Col;

        if(sizeof($Columns)) $Rows[] = $Columns;

        return $Rows;
    }


    /**
     * Faz a comparação de informação entre as colunas da tabela e as colunas definidas manulamente no modelo.
     * Retorna um array com as colunas preparadas para serem transformadas em campos de formulário
     * @return array
     */
    private function getColumns() : array
    {
        $Columns = [];
        foreach ($this->Model->columns as $Name => &$Field)
        {
            if(!$Field['form'])
                continue;

            # Ignorando colunas não permitidas
            if(in_array($Name, $this->NoAllow))
                continue;

            $Field['name'] = $Name;
            $Field['data_type'] = $Field['type'];
            if(in_array($Name, array_keys($this->Model->modalColumns)))
            {
                $cInfo = $this->Model->modalColumns[$Name];
                foreach ($cInfo as $Key => $Val)
                    $Field[$Key] = $Val;
            }

            if(in_array($Field['type'], ['select','radio','checkbox']))
                $Field['options'] = $this->getFieldOptions($Name, $Field);

            $Columns[$Name] = $Field;
        }
        return $Columns;
    }


    /**
     * Retorna as opções válidas de escolha para o campo especificado
     * @param string $Name
     * @param array $Field
     * @return array
     */
    private function getFieldOptions(string $Name, array $Field, $List = false) : array
    {
        if(isset($Field['options']))
            return $Field['options'];

        $Options = [];
        if(isset($Field['reference']))
        {
            $Reference = $Field['reference'];

            $ColumnValue = isset($Reference['colValue']) ? $Reference['colValue'] : 'Id';
            $ColumnLabel = isset($Reference['colName']) ? $Reference['colName'] : 'Nome';
            if(isset($Reference['table']))
            {
                $All = $this->Model->rawQuery("SELECT [{$ColumnValue}],[{$ColumnLabel}] FROM [TbTiposContaGrupo]");
                $All = sizeof($All) > 1 ? $All : [$All];
            }
            else
            {
                $Model = array_shift($Reference);
                if(class_exists($Model))
                    $All = $Model::all();
            }
            if(sizeof($All))
                foreach ($All as $Obj)
                    $Options[$Obj->{$ColumnValue}] = $Obj->{$ColumnLabel};
        }
        else
        {
            $Method = 'get'.ucfirst($Name).'Options';
            if(method_exists($this->Model,$Method))
                $Options = call_user_func_array([$this->Model,$Method],[]);
        }

        return $Options;
    }


    private function getValueList($Object, $Property)
    {
        $Info  = $Object->columns[$Property];
        $Type  = $Info['type'];
        $Value = $Object->$Property;
        preg_match('/(?<name>.+)Id$/', $Property, $Match);

        if(isset($Info['callback']))
        {
            if(method_exists($Object, $Info['callback']))
                return $Object->$Info['callback']($Property, $Value);
        }

        $Refer = function($Param) use ($Info, $Object){
            $colName = 'Nome';
            $colValue = 'Id';
            $table = null;
            $model = null;
            if(isset($Info['reference']))
            {
                $r = $Info['reference'];
                $colName  = isset($r['colName']) ? $r['colName'] : $colName;
                # $colFk = isset($r['colFk']) ? $r['colFk'] : 'Id';
                $colValue = isset($r['colValue']) ? $r['colValue'] : $colValue;
                if($Param == 'table')
                {
                    if(isset($r['table']))
                        $table = $r['table'];
                }
                if($Param=='model')
                {
                    if($class = array_shift($r))
                        if(class_exists($class))
                            $model = $class;
                }
            }
            return $$Param;
        };

        $Resolve = function() use ($Object, $Property, $Refer, $Match, $Info){
            $colName  = $Refer('colName');
            $colValue = $Refer('colValue');
            $value    = $Object->$Property;
            $table    = @$Refer('table');

            if(isset($Info['options']))
                return $Info['options'][$value];

            if($table)
            {
                $Res = $Object->rawQuery("SELECT {$colName} FROM {$table} WHERE {$colValue} = '{$value}'");
                if($Res)
                    return $Res->$colName;
            }
            else
            {
                if($Model=$Refer('model'))
                {
                    $Res = $Model::where($colValue, $value)->first();
                    if($Res)
                        return $Res->$colName;
                }
            }

            if(isset($Match['name']))
            {
                $Prop = $Match['name'];
                if($Res=$Object->$Prop)
                    return $Res->$colName;
            }


            $Method = 'get'.ucfirst($Property).'Options';
            if(method_exists($Object, $Method))
                return $Object->$Method()[$value];

        };

        if(in_array($Type, ['select', 'radio']))
            $Value = $Resolve();

        if(is_array($Value))
            $Value = implode(', ', $Value);

        if($Info['type_raw']=='datetime2')
        {
            $Format = ($f=@$Info['format']) ? $f : 'd/m/Y H:i';
            $Value = date($Format, strtotime($Value));
        }

        return $Value;
    }
}