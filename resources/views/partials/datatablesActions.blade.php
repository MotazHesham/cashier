
@if($crudRoutePart == 'orders')
    @php

        $setting = \App\Models\GeneralSetting::first();

        $cashier = json_decode($setting->cashier_printer);
        $kitchen = json_decode($setting->kitchen_printer);

        $cashier_printer = $cashier->printer ?? '';
        $kitchen_printer = $kitchen->printer ?? '';
        $cashier_printer_copies = $cashier->print_times ?? 1;
        $kitchen_printer_copies = $kitchen->print_times ?? 1;

        $link = route('admin.' . $crudRoutePart . '.print', $row->id); 
    @endphp    
    <a href="#" onclick="window.myAPI.sendDataToMainProcess(['{{$cashier_printer}}','{{$kitchen_printer}}','{{$link}}','{{$cashier_printer_copies}}','{{$kitchen_printer_copies}}'])" class="btn btn-outline-dark btn-pill action-buttons-print"  title="{{ trans('global.datatables.print') }}"><i  class="fas fa-print actions-custom-i"></i></a>

@endif
@can($viewGate)
    <a class="btn btn-outline-info btn-pill action-buttons-view" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}">
        <i  class="fas fa-eye actions-custom-i"></i>
    </a>
@endcan
@can($editGate)
    <a class="btn btn-outline-success btn-pill action-buttons-edit" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}">
        <i  class="fa fa-edit actions-custom-i"></i>
    </a>
@endcan
@can($deleteGate)
    <?php $route = route('admin.' . $crudRoutePart . '.destroy', $row->id); ?>
    <a  href="#" onclick="deleteConfirmation('{{$route}}')" class="btn btn-outline-danger btn-pill action-buttons-delete">
        <i  class="fa fa-trash actions-custom-i"></i>
    </a>
@endcan
