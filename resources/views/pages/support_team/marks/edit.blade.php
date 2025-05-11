<form class="ajax-update" action="{{ route('marks.update', [$exam_id, $my_class_id, $section_id, $subject_id]) }}" method="post">
    @csrf @method('put')
    <table class="table table-striped">
        <thead>
        <tr>
            <th>S/N</th>
            <th>Name</th>
            <th>ADM_NO</th>
            <th>1ST CA (20)</th>
            <th>2ND CA (20)</th>
            <th>EXAM (60)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($marks->sortBy('user.name') as $mk)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $mk->user->name }} </td>
                <td>{{ $mk->user->student_record->adm_no }}</td>

{{--                CA AND EXAMS --}} 
                <td><input title="1ST CA" min="0" max="20" class="text-center" name="t1_{{ $mk->id }}" value="{{ optional($mk)->t1 ?? 0 }}" type="number"></td>
                <td><input title="2ND CA" min="0" max="20" class="text-center" name="t2_{{ $mk->id }}"value="{{ optional($mk)->t2 ?? 0 }}"   type="number"></td>
                <td><input title="EXAM" min="0" max="60" class="text-center" name="exm_{{ $mk->id }}" value="{{ optional($mk)->exm ?? 0 }}" onclick="alert('This field is filled automically after executing the exam.')" readonly type="number"></td>

            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="text-center mt-2">
        <button type="submit" class="btn btn-primary">Update Marks <i class="icon-paperplane ml-2"></i></button>
    </div>
</form>
