
    <table class=" table table-bordered table-striped table-hover datatable ">
        <thead>
          <tr>
            <th>
              {{ trans('cruds.stock_operation.fields.quantity')}}
            </th>
            <th>
              {{ trans('cruds.stock_operation.fields.created_at')}}
            </th>
          </tr>
        </thead>
        <tbody>
          @foreach($stock->stockOperations as $operation)
            <tr>
              <td>
                {{ $operation->quantity }}
              </td>
              <td>
                {{ $operation->created_at }}
              </td>
            </tr>
          @endforeach
        </tbody>
    </table>
