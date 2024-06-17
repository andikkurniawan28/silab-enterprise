@extends('template.sneat.master')

@section('report-active')
    {{ 'active' }}
@endsection

@section('content')
    @csrf
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">

                <h4>Report</h4>

                <!-- Form with report_type dropdown and date & time inputs -->
                <form id="filterForm" action="{{ route('change_datetime') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="from_datetime" class="form-label">From</label>
                            <input type="datetime-local" class="form-control" id="from_datetime" name="from_datetime"
                                value="{{ session('from_datetime') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="to_datetime" class="form-label">To</label>
                            <input type="datetime-local" class="form-control" id="to_datetime" name="to_datetime"
                                value="{{ session('to_datetime') }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" id="filterButton">Filter</button>
                    <button type="button" class="btn btn-secondary btn-sm" id="printButton" onclick="printReport()">Print</button>
                    <button type="button" class="btn btn-success btn-sm" id="exportButton" onclick="exportToPDF()">Export to PDF</button>
                </form>

                {{-- Tampilkan kartu di sini --}}
                <div class="row mt-4" id="printable">
                    <div class="col-lg-12 col-md-12">
                        <div class="card mb-4 shadow">
                            <div class="card-header">
                                <h2 class="card-title">
                                    @if(isset($setup->company_logo) && $setup->company_logo)
                                        <img src="{{ asset($setup->company_logo) }}" alt="Company Logo" style="height: 100px; max-width: 200px;">
                                    @endif
                                </h2>
                            </div>
                            <div class="card-body">
                                <h5>{{ ucwords(str_replace('_', ' ', $report_type->name)) }}</h5>
                                <table>
                                    <tr>
                                        <td>From</td>
                                        <td>:</td>
                                        <td>{{ date("d-m-Y H:i:s", strtotime(session("from_datetime"))) }}</td>
                                    </tr>
                                    <tr>
                                        <td>To</td>
                                        <td>:</td>
                                        <td>{{ date("d-m-Y H:i:s", strtotime(session("to_datetime"))) }}</td>
                                    </tr>
                                </table>
                                <br>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>{{ ucwords(str_replace('_', ' ', 'material')) }}</th>
                                                @foreach ($parameters as $parameter)
                                                    <th>
                                                        {{ ucwords(str_replace('_', ' ', $parameter->parameter->name)) }}<sub>(@php echo $parameter->parameter->measurement_unit->name; @endphp)</sub>
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($materials as $material)
                                                <tr>
                                                    <td>{{ ucwords(str_replace('_', ' ', $material->name)) }}</td>
                                                    @foreach($parameters as $parameter)
                                                        @if($parameter->parameter->type == "Numeric")
                                                            <td>
                                                                {{
                                                                    $material->{ucwords(str_replace(' ', '_', $parameter->parameter->name))} != 0 ?
                                                                    number_format($material->{ucwords(str_replace(' ', '_', $parameter->parameter->name))}, $parameter->parameter->behind_decimal) :
                                                                    '-'
                                                                }}
                                                            </td>
                                                        @elseif($parameter->parameter->type == "Option")
                                                            <td>
                                                                @php
                                                                    $parameter_name = ucwords(str_replace(' ', '_', $parameter->parameter->name));
                                                                    $aggregated_values = $material->$parameter_name;
                                                                @endphp
                                                                @if(is_array($aggregated_values))
                                                                    <ul>
                                                                        @foreach($aggregated_values as $option_name => $count)
                                                                            <li>{{ $option_name }}: {{ $count }}%</li>
                                                                        @endforeach
                                                                    </ul>
                                                                @else
                                                                    {{ '-' }}
                                                                @endif
                                                            </td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('additional_script')
    <script>
        function printReport() {
            var printContents = document.getElementById('printable').innerHTML;
            var newWindow = window.open('', '_blank', 'width=800, height=600');
            newWindow.document.write('<html><head><title>Print Report</title>');
            newWindow.document.write('<link rel="stylesheet" href="{{ asset('sneat/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />');
            newWindow.document.write('<link rel="stylesheet" href="{{ asset('sneat/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />');
            newWindow.document.write('<link rel="stylesheet" href="{{ asset('sneat/assets/css/demo.css') }}" />');
            newWindow.document.write('</head><body>');
            newWindow.document.write(printContents);
            newWindow.document.write('</body></html>');
            newWindow.document.close();
            newWindow.onload = function() {
                newWindow.print();
                newWindow.close();
            };
        }

        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const elementHTML = document.getElementById('printable');
            const options = {
                callback: function (doc) {
                    doc.save('{{ ucwords(str_replace('_', ' ', $report_type->name)) }}.pdf');
                },
                x: 10,
                y: 10,
                width: 180, // Adjust the width according to your content
                windowWidth: 650 // Adjust the window width for scaling
            };
            html2canvas(elementHTML, {
                scale: 2
            }).then((canvas) => {
                const imgData = canvas.toDataURL('image/png');
                doc.addImage(imgData, 'PNG', options.x, options.y, options.width, options.width * canvas.height / canvas.width);
                doc.save('{{ ucwords(str_replace('_', ' ', $report_type->name)) }}.pdf');
            });
        }
    </script>
@endsection
