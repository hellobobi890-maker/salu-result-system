@extends('public.layout')

@section('content')
    <div class="result-page">
        {{-- University Header --}}
        <div class="uni-header text-center">
            <div class="uni-logo">
                <img src="{{ asset('img/new-logo.png') }}" alt="SALU Logo">
            </div>
            <h1 class="uni-title">Shah Abdul Latif University, Khairpur (Mir's), Sindh-Pakistan</h1>
            <h2 class="uni-subtitle">RESULT INTIMATION</h2>
        </div>

        {{-- Verification Form --}}
        <div class="form-wrapper">
            <div class="verify-card">
                @if ($errors->any())
                    <div class="error-msg">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('public.home.verify') }}" id="resultForm">
                    @csrf

                    <div class="field-group">
                        <label class="field-label">Select your program:</label>
                        <select name="program_id" class="field-input">
                            <option value="">Select Program</option>
                            @foreach ($programs ?? [] as $program)
                                <option value="{{ $program->id }}" @selected((string)old('program_id', $selectedProgramId ?? '') === (string)$program->id)>{{ $program->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field-group">
                        <label class="field-label">Year:</label>
                        <select name="year_id" class="field-input">
                            <option value="">Select Year</option>
                            @foreach ($years ?? [] as $year)
                                <option value="{{ $year->id }}" @selected((string)old('year_id', $selectedYearId ?? '') === (string)$year->id)>{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field-group">
                        <label class="field-label">Reg No:</label>
                        <input name="reg_no" type="text" value="{{ old('reg_no', $regNo ?? '') }}" class="field-input" />
                    </div>

                    <div class="field-group">
                        <button type="submit" class="result-btn" id="resultBtn">Result</button>
                    </div>
                </form>

                {{-- Spinner (shows while loading) --}}
                <div class="spinner-overlay" id="spinnerOverlay">
                    <img src="{{ asset('assets/images/spinner.gif') }}" alt="Loading...">
                </div>

                {{-- Result Table (shows below form after search) --}}
                <div id="resultArea" @if(!$student) style="display:none" @endif>
                    @if ($student)
                        @if ($result)
                            @php
                                $resultData = is_array($result->result_data ?? null) ? $result->result_data : [];
                                $standardKeys = ['FATHER NAME', 'TYPE', 'GENDER', 'MARKS'];
                            @endphp
                            <div class="result-table-wrap">
                                <table class="result-table">
                                    <tbody>
                                        <tr>
                                            <td class="label-cell">Name</td>
                                            <td class="value-cell">{{ $student->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label-cell">FATHER NAME</td>
                                            <td class="value-cell">{{ $resultData['FATHER NAME'] ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label-cell">TYPE</td>
                                            <td class="value-cell">{{ $resultData['TYPE'] ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label-cell">GENDER</td>
                                            <td class="value-cell">{{ $resultData['GENDER'] ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label-cell">ROLL NO</td>
                                            <td class="value-cell">{{ $student->roll_no }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label-cell">REGISTRATION NO</td>
                                            <td class="value-cell">{{ $student->reference_no }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label-cell">Year</td>
                                            <td class="value-cell">{{ optional($student->year)->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label-cell">MARKS</td>
                                            <td class="value-cell">{{ $resultData['MARKS'] ?? '-' }}</td>
                                        </tr>

                                        @foreach ($resultData as $key => $value)
                                            @continue(in_array($key, $standardKeys, true))
                                            <tr>
                                                <td class="label-cell text-uppercase">{{ $key }}</td>
                                                <td class="value-cell">{{ is_array($value) ? json_encode($value) : $value }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="no-result-msg">
                                Result record abhi add nahi hua.
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('resultForm').addEventListener('submit', function() {
            // Hide any existing result
            var resultArea = document.getElementById('resultArea');
            if (resultArea) resultArea.style.display = 'none';

            // Show spinner
            var spinner = document.getElementById('spinnerOverlay');
            if (spinner) spinner.classList.add('active');

            // Disable button
            var btn = document.getElementById('resultBtn');
            if (btn) {
                btn.disabled = true;
                btn.textContent = 'Loading...';
            }
        });
    </script>
@endsection
