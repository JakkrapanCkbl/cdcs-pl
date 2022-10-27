<?php

namespace App\Http\Controllers\Cdcs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class CdcsController extends Controller
{   
    public function index(Request $request)
    {     
        
        $incomings = DB::table('vwShowGrid')
            ->where('ClassID','=','I')
            ->where('ShowContract','=','S1')
            ->where('RegisterID','not like','%IB%')
            ->orderBy('IssuedDate', 'DESC')
            ->limit(25)
            ->get();
            // for use paginate-----
            // ->paginate(50);
            // $incomings->appends($request->all());  //for paginate use
            //must remark code  <div class="pagination-block"> in view.blade
            // if ( is_null($incomings) ) {
            //     App::abort(404);
            //   }   

        $outgoings  = DB::table('vwShowGridOut')
            ->where('ClassID','=','O')
            ->where('ShowContract','=','S1')
            ->where('RegisterID','not like','%OB%')
            ->orderBy('IssuedDate', 'DESC')
            ->limit(25)
            ->get();
            //for use paginate----------
            // ->paginate(50);
            // $outgoings->appends($request->all()); //for paginate use
            //must remark code  <div class="pagination-block"> in view.blade
            // if ( is_null($outgoings) ) {
            //     App::abort(404);
            //   }   

        return view('cdcs.home',['incomings'=>$incomings, 'outgoings'=>$outgoings]);
    }
    
    function check(Request $request){
        // $request->validate([
        //     'loginname'=>'required|loginname|exists:users,loginname',
        //     'password'=>'required'
        // ]);

        $creds = $request->only('loginname','password');

        if(Auth::guard('cdcs')->attempt($creds)) {
            return redirect()->route('cdcs.home');
        }else{
            return redirect()->route('cdcs.login')->with('fail','Incorrect Credentials');
        }
    }

    function logout(){
        // dd('ok');
        Auth::guard('cdcs')->logout();
        return redirect('/');
    }

    public function search(Request $request) {
        // if(isset($_GET['inputText']) && strlen($_GET['inputText']) > 2) {
        // dd($request->get('inputContract') . 'xx' ); 

        $ct = $request->get('inputContract'); 
        $sf = $request->get('inputField');
        $input = $request->get('inputText');
        // dd($ct.$sf.$input);
        if ($ct == 'All') {
            $ct = '%';
        }
        if ($sf=='Subject (use a , to separate)') {
            $sf = 'DocSubject';
        }
        // dd($sf);
        if(isset($_GET['inputText'])) {
            $search_text = $_GET['inputText'];
            //case Subject
            if ($sf == 'DocSubject') {
                $myArray = explode(',', $search_text);
                //dd($myArray);
                $numItems = count($myArray);
                //dd($numItems);
                $strsql = "SELECT * FROM vwShowGrid ";
                $strsql = $strsql . "WHERE ClassID = 'I' ";
                $strsql = $strsql . "AND ShowContract like '%" . $ct . "%' ";
                if ($numItems > 1) {
                    foreach ($myArray as $key => $value) {
                        // echo "key = " . $key . ", value = " . $value . "\n";
                        if($key == 0) {
                            $strsql = $strsql . "AND (DocSubject LIKE '%" . trim($value) . "%' ";
                        }elseif($key == ($numItems - 1)) {
                            $strsql = $strsql . "AND DocSubject LIKE '%" . trim($value) . "%') ";
                        }
                        else{
                            $strsql = $strsql . "AND DocSubject LIKE '%" . trim($value) . "%' ";
                        }
                    }
                }
                $strsql = $strsql . "ORDER BY IssuedDate DESC ";
                // echo $strsql;
                $incomings = DB::select($strsql);
                //------------------------------------------------------------------------------------
                // $myArray = explode(',', $search_text);
                // $numItems = count($myArray);
                $strsql = "SELECT * FROM vwShowGridOut ";
                $strsql = $strsql . "WHERE ClassID = 'O' ";
                $strsql = $strsql . "AND ShowContract like '%" . $ct . "%' ";
                if ($numItems > 1) {
                    foreach ($myArray as $key => $value) {
                        if($key == 0) {
                            $strsql = $strsql . "AND (DocSubject LIKE '%" . trim($value) . "%' ";
                        }elseif($key == ($numItems - 1)) {
                            $strsql = $strsql . "AND DocSubject LIKE '%" . trim($value) . "%') ";
                        }
                        else{
                            $strsql = $strsql . "AND DocSubject LIKE '%" . trim($value) . "%' ";
                        }
                    }
                }
                $strsql = $strsql . "ORDER BY IssuedDate DESC ";
                $outgoings = DB::select($strsql);
                
            }else{ //case simple
                $incomings = DB::table('vwShowGrid')
                ->where('ClassID','=','I')
                ->where('RegisterID','not like','%IB%')
                ->where('ShowContract', 'like','%'.$ct.'%')
                ->where(function($query) use ($sf, $search_text){
                    $query->where($sf,'like','%'. $search_text .'%');
                })
                ->orderBy('IssuedDate', 'DESC')
                ->get();
                // ->paginate(10);
                // $incomings->appends($request->all());
                //--------------------------------------------------------------------
                $outgoings  = DB::table('vwShowGridOut')
                    ->where('ClassID','=','O')
                    ->where('RegisterID','not like','%OB%')
                    ->where('ShowContract', 'like','%'.$ct.'%')
                    ->where(function($query) use ($sf, $search_text){
                        $query->where($sf,'like','%'.$search_text.'%');
                    })
                    ->orderBy('IssuedDate', 'DESC')
                    ->get();
                    // ->paginate(10);
                    // $outgoings->appends($request->all());
            }
         
            return view('cdcs.home',['incomings'=>$incomings, 'outgoings'=>$outgoings, 'ct'=>$ct, 'sf'=>$sf, 'inputs'=>$input]);
        }
    }

    public function view_pdf_list($id){
        // dd(unserialize($id));
        $myString = unserialize($id);
        $myArray = explode(',', $myString);
        // dd($myArray);
        $count = count($myArray);
        // dd($count);
        if ($count > 1) {
            echo "<div style='
            background-color: lightgrey;
            max-width: 500px;
            margin: auto;
            background: LightGray;
            padding: 10px;
            '>";
            echo "Respond to :<br>";
            foreach($myArray as $x => $val) {
                //echo "$x = $val<br>";
                //echo "$val<br>";
                // dd($val);
                //return $this->view_pdf($val);
                //return redirect()->route('cdcs.viewpdf', ['id' => $val]);
                // route('cdcs.viewpdf', ['id' => $val]);
                // <a href="{{ route('cdcs.viewpdf', ['id' => $incoming->RegisterID])}}" target="_blank">{{ $incoming->RegisterID }}</a>
                // echo route('cdcs.viewpdf', ['id' => $val]);
                // echo "<h4>Respond to : </h4>";
                echo "<a href='" . route('cdcs.viewpdf', ['id' => trim($val)]) ."' target='_blank'>" . trim($val) . "</a><br>";
            }
            echo "</div>";
        }else{
            return $this->view_pdf($myArray[0]);
        }
    }

    public function view_pdf($id)
    {
        //dd($id);
        if (($id == null) || ($id == '')) {
            return redirect()->back();
        }

        $docconf = $this->CheckDocConfidentail($id);
        // dd($docconf);

        if ($docconf == '1') {
            // dd('aut conf = '.Auth::user()->ViewConfidential);
            // Auth::guard('it')->user()->LoginName
            if (Auth::guard('cdcs')->user()->ViewConfidential) {
                $isopen = '1';
            }else{
                $isopen = '0';
            }
        }else{
            $isopen = '1';
        }
        // dd('is open = '.$isopen);

        if ($isopen == '1') {
            // substr("DC02-00-OE-PM02-00002", 0, 2);
            //check is registerid or sender_ref
            if ($this->CheckIsRegId($id) == '1'){
                $id = $id;
            }else{
                //dd('is sender_ref');
                //case INCOMING referto  (and outgoing is registerid = DC03-02-OE-PM03-00032
                $keep_id = $id;
                $id = $this->MyFind("ReferToDoc",
                "RegisterID", 
                "WHERE ReferTo = '" . $id . "' AND substring(RegisterID,9,1) = 'O'", 
                "ORDER BY RegisterID");

                if ($id ==  null) {
                    // case OUTGOING responde to
                    $id = $this->MyFind("RegisterDoc",
                    "RegisterID", 
                    "WHERE CrossRef = '" . $keep_id . "' ", 
                    "ORDER BY RegisterID");
                }
                //  dd($id);
            }
            $mm = substr($id, 5, 2);
            // dd($mm);
            // get MM-YYYY
            $mm_yyyy = $this->GetMY_Folder($mm);
            // dd($mm_yyyy);
            // get fn
            $fn = $this->GetFn($id);
            // dd($fn);
            //  $fullpath = 'storage/cdcs-ppls/docs/00-2022/PLS1-00-IB-BUDG-00001/PLS1-00-IB-BUDG-00001-742266341.pdf';
            // storage/cdcs-ppls/docs/00-2022/PLS1-00-IB-BUDG-00001/PLS1-00-IB-BUDG-00001-742266341.pdf
            // storage/cdcs-ppls/docs/01-2022/PLS1-01-IM-CONT-00018/PLS1-01-IM-CONT-00018-30APR22-01.pdf
            //$fullpath = 'storage/cdcs-ppls/docs/'.$mm_yyyy.'/'.$id.'/'.$fn;
            // $accesskey = "?sv=2020-08-04&ss=f&srt=sco&sp=rwdlc&se=2027-12-31T11:19:49Z&st=2022-03-16T03:19:49Z&spr=https&sig=BH4be9fKMnR%2BmnTd%2FPwdnJbOMleYYpAS%2BywaTpank60%3D";
            
            // $accesskey = env("AZURE_ACCESS_KEY");
            // // dd($accesskey);
            // $fullpath = Storage::disk('azure')->url('').'Letters/'.$mm_yyyy.'/'.$id.'/'.$fn.$accesskey;

            $fullpath = asset('cdcs-ppls/docs/'.$mm_yyyy.'/'.$id.'/'.$fn);
            
            // dd($fullpath);
            
            return view('cdcs.viewpdf',[
                'id' => $id,
                'fullpath' => $fullpath 
            ]);
            // return redirect()->away($fullpath);    

        }else{
            return redirect()->back() ->with('alert', 'Confidentail Document! ');
        }
        
       
    }

    public function CheckIsRegId($id) {
        //DC02-00-OE-PM02-00002
        $set1 = substr($id, 4, 1);
        $set2 = substr($id, 7, 1);
        $set3 = substr($id, 10, 1);
        $set4 = substr($id, 15, 1);

        if(($set1 == '-') && ($set2 == '-') && ($set3 == '-') && ($set4 == '-')) {
            return 1;
        }else{
            return 0;
        }
    }

    public function MyFind($TableName, $FieldOut, $StrFilter, $OrderBy) {
        $strsql = "SELECT " . $FieldOut . " FROM " . $TableName;
        if($StrFilter != ''){
            $strsql = $strsql . " " . $StrFilter;
        }
        if($OrderBy != ''){
            $strsql = $strsql . " " . $OrderBy;
        }
        $result = DB::select($strsql);
        
        if ($result == null) {
            // dd("null");
            return null;
        }else {
            return $result[0]->$FieldOut;
        }
    }


    public function lineview_pdf($id)
    {
        //dd($id);
        if (($id == null) || ($id == '')) {
            return redirect()->back();
        }

        $docconf = $this->CheckDocConfidentail($id);
        // dd($docconf);
        if ($docconf == '1') {
            // dd('aut conf = '.Auth::user()->ViewConfidential);
            // Auth::guard('it')->user()->LoginName
            if (Auth::guard('cdcs')->user()->ViewConfidential) {
                $isopen = '1';
            }else{
                $isopen = '0';
            }
        }else{
            $isopen = '1';
        }
        // dd('is open = '.$isopen);

        if ($isopen == '1') {
            if ($this->CheckIsRegId($id) == '1'){
                $id = $id;
            }else{
                //dd('is sender_ref');
                //case INCOMING referto  (and outgoing is registerid = DC03-02-OE-PM03-00032
                $keep_id = $id;
                $id = $this->MyFind("ReferToDoc",
                "RegisterID", 
                "WHERE ReferTo = '" . $id . "' AND substring(RegisterID,9,1) = 'O'", 
                "ORDER BY RegisterID");

                if ($id ==  null) {
                    // case OUTGOING responde to
                    $id = $this->MyFind("RegisterDoc",
                    "RegisterID", 
                    "WHERE CrossRef = '" . $keep_id . "' ", 
                    "ORDER BY RegisterID");
                }
                //  dd($id);
            }
            $mm = substr($id, 5, 2);
            // dd($mm);
            // get MM-YYYY
            $mm_yyyy = $this->GetMY_Folder($mm);
            // dd($mm_yyyy);
            // get fn
            $fn = $this->GetFn($id);
            // dd($fn);
            $subject = DB::table('RegisterDoc')->where('RegisterID', $id)->first();
            $fullpath = asset('cdcs-ppls/docs/'.$mm_yyyy.'/'.$id.'/'.$fn);
            
            return view('cdcs.lineviewpdf',[
                'id' => $id,
                'fullpath' => $fullpath,
                'subject' => $subject->DocSubject,
                'contents'=> [$subject->CrossRef,$subject->IssuedDate]
            ]);

        }else{
            return redirect()->back() ->with('alert', 'Confidentail Document! ');
        }
        
    }

    public function TestDisk(){
        // Storage::disk('azure')->download($fullpath,$fn,$headers);
            
        $path = 'Letters/'.$mm_yyyy.'/'.$id.'/';
 
        // Get the Larvel disk for Azure
        $disk = Storage::disk('azure');
 
        // List files in the container path
        $files = $disk->files($path);

        // Storage::download($files[0]);
        // dd('done');
 
        // create an array to store the names, sizes and last modified date
        $list = array();
 
        // Process each filename and get the size and last modified date
        foreach($files as $file) {

                $x = $disk->download($file,'x.pdf', ['Content-Type: application/pdf']);
                // dd('done');
 
                $size = $disk->size($file);
 
                $modified = $disk->lastModified($file);
                $modified = date("Y-m-d H:i:s", $modified);
 
                $filename = "$file";
 
                $item = array(
                        'name' => $filename,
                        'size' => $size,
                        'modified' => $modified,
                        'x' => $x,
                );
 
                array_push($list, $item);
               
        }
 
        $results = json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
 
        return response($results)->header('content-type', 'application/json');
    }

    public function GetMY_Folder($mm) {
        $result = DB::table('ProjectCalendar')
        ->where('ProjectMonth','=',$mm)
        ->first();
        if ($result == null) {
            return '00-2022';
        }else{
            return $mm.'-'.$result->RealYear;
        }
      
    }

    public function GetFn($id) {
        $result = DB::table('Register_Files')
        ->where('RegisterID','=',$id)
        ->where('FileName','like','%.pdf')
        ->first();
        if ($result == null) {
            return redirect()->back() ->with('alert', $id.' is nothing.');
        }else{
            return $result->FileName;
        }
       
    }

    public function CheckDocConfidentail($id) {
            $result = DB::table('RegisterDoc')
            ->where('RegisterID','=',$id)
            ->first();
            if ($result == null) {
                return redirect()->back() ->with('alert', $id.' is nothing.');
            }else{
                return $result->Confidential;
            }
    }

    public function show($id) {
        // return $id;
        $data = [
            '1' => 'PLS1-00-IB-BUDG-00001-742266341.pdf',
            '2' => 'PLS1-00-IB-BUDG-00002-742267544.pdf',
            '3' => 'PLS1-00-IM-CM1A-00005-184221122179.pdf'
        ];
        // dd($data[$id]);
        
        if (Storage::disk('share-drive')->exists('file.txt')) {
            dd('OK');
        }else{
            return view('viewpdf',[
                'filename' => $data[$id] ?? 'file name ' . $id 
            ]);
        }
    }

    public function getdrive(){
        // dd(Storage::disk('azure'));
        // https://dccrstorage.file.core.windows.net/docs/Letters/00-2022/DC02-00-IE-PM02-00001/DC02-00-IE-PM02-000011632228137.pdf
        if (Storage::disk('azure')->exists('test1.pdf')) {
            // dd('okx');
            $contents = Storage::disk('azure')->url('test1.pdf');
            dd($contents);
        }
        else{
            dd('nothing x');
        }
        return true;
    }

    public function downloadFile()
    {   	
        // $myFile = public_path("dummy.pdf");
        $myFile = "https://dccrstorage.file.core.windows.net/docs/Letters/04-2022/DC02-04-IE-PM02-00033/DC02-04-IE-PM02-000332552233879.pdf?sv=2020-08-04&ss=f&srt=sco&sp=rwdlc&se=2027-12-31T11:19:49Z&st=2022-03-16T03:19:49Z&spr=https&sig=BH4be9fKMnR%2BmnTd%2FPwdnJbOMleYYpAS%2BywaTpank60%3D";
    	return response()->download($myFile);
    }

    public function dwg_list(Request $request)
    {     
        
        $strsql = "SELECT * FROM vwDwgReg_List_Step2_Short ";
        $strsql = $strsql . "WHERE (ShowContract like '1') ";
        $strsql = $strsql . "AND (Revision = 'B') ";
        $strsql = $strsql . "AND (DwgCancel = 0) ";
        $drawings = DB::select($strsql);

        return view('cdcs.dwg_list',['drawings'=>$drawings]);
    }

}
