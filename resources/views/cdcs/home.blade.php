@extends('layouts.cdcs')

@section('content')
    <!-- Page Menu -->
    <div class="page-menu menu-outline">
        <div class="container">
            <nav>
                <ul>
                    <li><a href="page-menu.html">Memo</a></li>
                    <li><a href="page-menu-sticky.html">Advance Search</a></li>
                    <li><a href=" {{ url('cdcs/dwg_list') }} ">Drawing</a></li>
                </ul>
            </nav>
            <div id="pageMenu-trigger">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </div>
    <!-- end: Page Menu -->
    <section id="page-content">
            <div class="container">
                <div class="row">
                    <div class="content col-lg-12">
                        <form class="row g-3" method="GET" action="{{ route('cdcs.search') }}" >
                            @if(Session::has('message'))
                                <div class="alert alert-success">{{Session::get('message')}}</div>
                            @endif
                            <div class="col-md-1">
                                <label for="inputContract" class="form-label">Contract</label>
                                <select name="inputContract" id="inputContract" class="form-select">
                                    @if(isset($ct))
                                        @if($ct == 'S1')
                                            <option selected>S1</option>
                                            <option>S2</option>
                                            <option>All</option>
                                        @elseif($ct == 'S2')
                                            <option>S1</option>
                                            <option selected>S2</option>
                                            <option>All</option>
                                        @else
                                            <option>S1</option>
                                            <option>S2</option>
                                            <option selected>All</option>
                                        @endif
                                    @else
                                        <option selected>S1</option>
                                        <option>S2</option>
                                        <option>All</option>
                                    @endif
                              </select>
                            </div>
                            <div class="col-md-3">
                                <label for="inputField" class="form-label">Field</label>
                                <select name="inputField" id="inputField" class="form-select">
                                    @if(isset($sf))
                                        @if($sf == 'RegisterID')
                                            <option selected>RegisterID</option>
                                            <option>Subject (use a , to separate)</option>
                                        @elseif($sf == 'DocSubject')
                                            <option>RegisterID</option>
                                            <option selected>Subject (use a , to separate)</option>
                                        @endif
                                    @else
                                        <option selected>RegisterID</option>
                                        <option>Subject (use a , to separate)</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="inputText" class="form-label">Input</label>
                                @if(isset($inputs))
                                    <input type="text" class="form-control" name="inputText" id="inputText" autocorrect="off" autocapitalize="none" value="{{$inputs}}">                       
                                @else
                                    <input type="text" class="form-control" name="inputText" id="inputText" autocorrect="off" autocapitalize="none">
                                @endif                      
                            </div>
                           
                            <div class="col-md-2">
                                <label for="btnSearch" class="form-label">Search</label>
                                <button type="submit" class="btn btn-primary" id="btnSearch">Search now</button>
                            </div>

                            <div>
                                 {{-- show datatable 1 incoming --}}
                                <table class="table table-bordered nobottommargin" id="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th class="text-center">RegisterID</th>
                                            <th class="text-center">IssuedDate</th>
                                            <th class="text-center">From</th>
                                            <th class="text-center">To</th>
                                            <th class="text-center">Subject</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Sender Ref</th>
                                            <th class="text-center">Respond to</th>
                                            <th class="text-center">Refer to</th>
                                            <th class="text-center">Consult Response</th>
                                            <th class="text-center">Responded by</th>
                                            <th class="text-center">Document code : Document name</th>
                                            <th class="text-center">Transmittal no</th>
                                            <th class="text-center">Inform to</th>
                                            <th class="text-center">Remark</th>
                                            <th class="text-center">Doc Register</th>
                                            <th class="text-center">Doc Category</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($incomings) >= 1)
                                            @foreach($incomings as $incoming)
                                                <tr>
                                                    <td>
                                                        <a id="todo{{ $incoming->RegisterID }}" class="open-ViewTodo btn btn-light btn-xs" data-bs-target="#modal-3" 
                                                        data-bs-toggle="modal" 
                                                        data-todo='{
                                                            "regid":"{{ $incoming->RegisterID }}",
                                                            "crossref":"{{ $incoming->CrossRef }}",
                                                            "subject":"{{ $incoming->DocSubject }}",
                                                            "issueddate":"{{ date('d-M-y', strtotime($incoming->IssuedDate)) }}",
                                                            "docstatus":"{{ $incoming->DocStatus }}",
                                                            "docfrom":"{{ $incoming->DocFrom }}",
                                                            "docto":"{{ $incoming->DocTo }}",
                                                            "responsetodocument":"{{ $incoming->ResponseToDocument }}",
                                                            "link_responsetodocument":"{{ $url = route('cdcs.viewpdflist', ['id' => serialize($incoming->ResponseToDocument)]); }}",
                                                            "referto":"{{ $incoming->ReferTo }}",
                                                            "link_referto":"{{ $url = route('cdcs.viewpdflist', ['id' => serialize($incoming->ReferTo)]); }}",
                                                            "csc_response":"{{ $incoming->CSC_Response }}",
                                                            "showresponsed":"{{ $incoming->ShowResponsed }}",
                                                            "link_showresponsed":"{{ $url = route('cdcs.viewpdflist', ['id' => serialize($incoming->ShowResponsed)]); }}",
                                                            "showdoccode":"{{ $incoming->ShowDocCode }}",
                                                            "showtransmittalno":"{{ $incoming->ShowTransmittalNo }}",
                                                            "rn":"{{ $url = route('cdcs.viewpdf', ['id' => $incoming->RegisterID]); }}"
                                                            }'
                                                        href="#"><i class="icon-eye"></i></a>
                                                        
                                                    </td>
                                                    <td>
                                                        {{-- <a href="{{ route('cdcs.viewpdf', ['id' => $incoming->RegisterID])}}" target="_blank">
                                                            {{ $incoming->RegisterID }}
                                                        </a> --}}
                                                        @if((new \Jenssegers\Agent\Agent())->isDesktop())
                                                            <a href="{{ route('cdcs.viewpdf', ['id' => $incoming->RegisterID])}}" target="_blank">
                                                                {{ $incoming->RegisterID }}
                                                            </a>
                                                        @elseif((new \Jenssegers\Agent\Agent())->isMobile())
                                                            <a href="{{ route('cdcs.mobileviewpdf', ['id' => $incoming->RegisterID])}}" target="_blank">
                                                                {{ $incoming->RegisterID }}
                                                            </a>
                                                        @elseif((new \Jenssegers\Agent\Agent())->isTablet())
                                                            <a href="{{ route('cdcs.mobileviewpdf', ['id' => $incoming->RegisterID])}}" target="_blank">
                                                                {{ $incoming->RegisterID }}
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td><p>{{ date('d-M-y', strtotime($incoming->IssuedDate)) }}</p></td>
                                                    <td><p data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="From : {{ $incoming->DocFrom }}">{{ Str::limit($incoming->DocFrom, 10) }}</p></td>
                                                    <td><p data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="To : {{ $incoming->DocTo }}">{{ Str::limit($incoming->DocTo, 20) }}</p></td>
                                                    <td><p data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $incoming->DocSubject }}">{{ $incoming->DocSubject }}</p></td>
                                                    <td><p>{{ $incoming->DocStatus }}</p></td>
                                                    <td><p>{{ $incoming->CrossRef }}</p></td>
                                                    <td><p>{{ $incoming->ResponseToDocument }}</p></td>
                                                    <td><p>{{ $incoming->ReferTo }}</p></td>
                                                    <td><p>{{ $incoming->CSC_Response }}</p></td>
                                                    <td><p>{{ $incoming->ShowResponsed }}</p></td>
                                                    <td><p data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="doc code : code name > {{ $incoming->ShowDocCode }}">{{ Str::limit($incoming->ShowDocCode, 100) }}</p></td>
                                                    <td><p>{{ $incoming->ShowTransmittalNo }}</p></td>
                                                    <td><p data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Inform to : {{ $incoming->InformTo }}">{{ Str::limit($incoming->InformTo, 20) }}</p></td>
                                                    <td><p>{{ $incoming->Remark }}</p></td>
                                                    <td><p>{{ date('d-M-y', strtotime($incoming->RegDate)) }}</p></td>
                                                    <td><p>{{ $incoming->ShowDocCat }}</p></td>
                                                    {{-- <td><button class="edit-modal btn btn-info"
                                                        data-info="{{$item->id}},{{$item->first_name}},{{$item->last_name}},{{$item->email}},{{$item->gender}},{{$item->country}},{{$item->salary}}">
                                                        <span class="glyphicon glyphicon-edit"></span> Edit
                                                    </button>
                                                    <button class="delete-modal btn btn-danger"
                                                        data-info="{{$item->id}},{{$item->first_name}},{{$item->last_name}},{{$item->email}},{{$item->gender}},{{$item->country}},{{$item->salary}}">
                                                        <span class="glyphicon glyphicon-trash"></span> Delete
                                                    </button></td> --}}
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr><td>No result found!</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                                {{-- end show datatable 1 incoming --}}
                            </div>
                           
                            <div>
                                 {{-- show datatable 2 incoming --}}
                                <table class="table table-bordered nobottommargin" id="table2">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>RegisterID</th>
                                            <th>IssuedDate</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                            <th>Refer to</th>
                                            <th>Responde to</th>
                                            <th>Responded by</th>
                                            <th>Document code : Document name</th>
                                            <th>Transmittal no</th>
                                            <th>Inform to</th>
                                            <th>Remark</th>
                                            <th>Doc Register</th>
                                            <th>Doc Category</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($outgoings) >= 1)
                                            @foreach($outgoings as $outgoing)
                                               <tr class="table-secondary">
                                                    <td>
                                                        <a id="todo{{ $outgoing->RegisterID }}" class="open-ViewTodoOut btn btn-light btn-xs" data-bs-target="#modal-2" 
                                                        data-bs-toggle="modal" 
                                                        data-todo='{
                                                            "regidout":"{{ $outgoing->RegisterID }}",
                                                            "subjectout":"{{ $outgoing->DocSubject }}",
                                                            "issueddate":"{{ date('d-M-y', strtotime($outgoing->IssuedDate)) }}",
                                                            "docstatus":"{{ $outgoing->DocStatus }}",
                                                            "docfrom":"{{ $outgoing->DocFrom }}",
                                                            "docto":"{{ $outgoing->DocTo }}",
                                                            "referto":"{{ $outgoing->ReferTo }}",
                                                            "link_referto":"{{ $url = route('cdcs.viewpdflist', ['id' => serialize($outgoing->ReferTo)]); }}",
                                                            "responsetodocument":"{{ $outgoing->ResponseToDocument }}",
                                                            "link_responsetodocument":"{{ $url = route('cdcs.viewpdflist', ['id' => serialize($outgoing->ResponseToDocument)]); }}",
                                                            "showresponsed":"{{ $outgoing->ShowResponsed }}",
                                                            "link_showresponsed":"{{ $url = route('cdcs.viewpdflist', ['id' => serialize($outgoing->ShowResponsed)]); }}",
                                                            "showdoccode":"{{ $outgoing->ShowDocCode }}",
                                                            "showtransmittalno":"{{ $outgoing->ShowTransmittalNo }}",
                                                            "rnout":"{{ $url = route('cdcs.viewpdf', ['id' => $outgoing->RegisterID]); }}"
                                                            }'
                                                        href="#"><i class="icon-eye"></i></a>
                                                        
                                                    </td>
                                                    <td>
                                                        {{-- <a href="{{ route('cdcs.viewpdf', ['id' => $outgoing->RegisterID])}}" target="_blank">
                                                            {{ $outgoing->RegisterID }}
                                                        </a> --}}
                                                        @if((new \Jenssegers\Agent\Agent())->isDesktop())
                                                            <a href="{{ route('cdcs.viewpdf', ['id' => $outgoing->RegisterID])}}" target="_blank">
                                                                {{ $outgoing->RegisterID }}
                                                            </a>
                                                        @elseif((new \Jenssegers\Agent\Agent())->isMobile())
                                                            <a href="{{ route('cdcs.mobileviewpdf', ['id' => $outgoing->RegisterID])}}" target="_blank">
                                                                {{ $outgoing->RegisterID }}
                                                            </a>
                                                        @elseif((new \Jenssegers\Agent\Agent())->isTablet())
                                                            <a href="{{ route('cdcs.mobileviewpdf', ['id' => $outgoing->RegisterID])}}" target="_blank">
                                                                {{ $outgoing->RegisterID }}
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td><p>{{ date('d-M-y', strtotime($outgoing->IssuedDate)) }}</p></td>
                                                    <td><p data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="From : {{ $outgoing->DocFrom }}">{{ Str::limit($outgoing->DocFrom, 10) }}</p></td>
                                                    <td><p data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="To : {{ $outgoing->DocTo }}">{{ Str::limit($outgoing->DocTo, 20) }}</p></td>
                                                    <td><p data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $outgoing->DocSubject }}">{{ $outgoing->DocSubject }}</p></td>
                                                    <td><p>{{ $outgoing->DocStatus }}</p></td>
                                                    <td><p>{{ $outgoing->ReferTo }}</p></td>
                                                    <td><p>{{ $outgoing->ResponseToDocument }}</p></td>
                                                    <td><p>{{ $outgoing->ShowResponsed }}</p></td>
                                                    <td><p data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="doc code : code name > {{ $outgoing->ShowDocCode }}">{{ Str::limit($outgoing->ShowDocCode, 100) }}</p></td>
                                                    <td><p>{{ $outgoing->ShowTransmittalNo }}</p></td>
                                                    <td><p data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Inform to : {{ $outgoing->InformTo }}">{{ Str::limit($outgoing->InformTo, 20) }}</p></td>
                                                    <td><p>{{ $outgoing->Remark }}</p></td>
                                                    <td><p>{{ date('d-M-y', strtotime($outgoing->RegDate)) }}</p></td>
                                                    <td><p>{{ $outgoing->ShowDocCat }}</p></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr><td>No result found!</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                                {{-- end show datatable 2 incoming --}}
                            </div>


                        </form>
                        {{-- end form --}}
                    </div>
                    {{-- content col-lg-12 --}}
                </div> 
                {{-- end row --}}
            </div>
            {{-- end container --}}
            
    </section>





    <!--Modal large -->
    <div class="modal fade" id="modal-3" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-label-3" class="modal-title"><p id="mbdRegID" style="font-size: 20px;text-decoration: underline;"></p></h4>
                    <button aria-hidden="true" data-bs-dismiss="modal" class="btn-close" type="button">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- <input type="text" name="regid" id="regid" value="" class="form-control" style="width: 230px;"> --}}                    
                            <p id="mbdCrossRef"></p>
                            <p id="mbdSubject"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><p id="mbdIssuedDate"></p></div>
                        <div class="col-md-6"><p id="mbdDocStatus"></p></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p id="mbdDocFrom"></p>
                            <p id="mbdDocTo"></p>
                        </div>
                    </div>
                    <div class="row">
                        {{-- <div class="col-md-2"><p>Respond to : </p></div>
                        <div class="col-md-10"><p id="mbdResponseToDocument"></p></div> --}}
                         <div class="col-md-2"><p>Respond to : </p></div>
                        <div class="col-md-10"><p id="mbdLink_ResponseToDocument" style="color:blue;text-decoration: underline;"></p></div>
                        {{-- <div class="col-md-2"><p>Refer to : </p></div>
                        <div class="col-md-10"><p id="mbdReferTo" style="color:blue;text-decoration: underline;"></p></div> --}}
                        <div class="col-md-2"><p>Refer to : </p></div>
                        <div class="col-md-10"><p id="mbdLink_ReferTo" style="color:blue;text-decoration: underline;"></p></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p id="mbdCSC_Response"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><p>Responded by : </p></div>
                        <div class="col-md-9"><p id="mbdLink_ShowResponsed" style="color:blue;text-decoration: underline;"></p></div>
                    </div>
                     <div class="row">
                        <div class="col-md-12">
                            <p id="mbdShowDocCode"></p>
                            <p id="mbdShowTransmittalNo"></p>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button data-bs-dismiss="modal" class="btn btn-b" type="button">Close</button>
                    {{-- <button class="btn btn-b" type="button">View PDF</button> --}}
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal-2" tabindex="-1" role="modal" aria-labelledby="modal-label-2" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-label-2" class="modal-title"><p id="mbdRegID_Out" style="font-size: 20px;text-decoration: underline;"></p></h4>
                    <button aria-hidden="true" data-bs-dismiss="modal" class="btn-close" type="button">×</button>
                    {{-- <button aria-hidden="true" data-bs-dismiss="modal" class="btn-close" type="button">×</button> --}}
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p id="mbdSubject_Out"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><p id="mbdIssuedDate_Out"></p></div>
                        <div class="col-md-6"><p id="mbdDocStatus_Out"></p></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><p id="mbdDocFrom_Out"></p></div>
                        <div class="col-md-6"><p id="mbdDocTo_Out"></p></div>
                    </div>
                   
                    <div class="row">
                        <div class="col-md-2"><p>Refer to : </p></div>
                        <div class="col-md-10"><p id="mbdLink_ReferTo_Out" style="color:blue;text-decoration: underline;"></p></div>
                        <div class="col-md-2"><p>Respond to : </p></div>
                        <div class="col-md-10"><p id="mbdLink_ResponseToDocument_Out" style="color:blue;text-decoration: underline;"></p></div>                          
                    </div>
                    <div class="row">
                        <div class="col-md-3"><p>Responded by : </p></div>
                        <div class="col-md-9"><p id="mbdLink_ShowResponsed_Out" style="color:blue;text-decoration: underline;"></p></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p id="mbdShowDocCode_Out"></p>
                            <p id="mbdShowTransmittalNo_Out"></p>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button data-bs-dismiss="modal" class="btn btn-b" type="button">Close</button>
                    {{-- <button class="btn btn-b" type="button">View PDF</button> --}}
                </div>
            </div>
        </div>
    </div>
    
    <!-- end: Modal large -->
@endsection
