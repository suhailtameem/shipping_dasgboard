<?php

namespace App\Http\Controllers;
// use App\Http\Controllers\listsControllrt as Lists;
use Illuminate\Http\Request;
use App\Models\{sysLists, lists, subList, cusNotif, features};
use Illuminate\Support\Facades\Session;

class listsControllrt extends Controller
{

    public function indexSystemLists()
    {
        $sysLists = sysLists::with('options.subLists')->get();
        return view('external.sys-lists', compact('sysLists'));
    }

    public function storeSystemList(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
        ]);
        sysLists::create([
            'name'     => $request->name,
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
        ]);
        return redirect()->back()->with(['status' => 'List created successfully', 'stype' => 'success']);
    }

    public function updateSystemList(Request $request)
    {
        $request->validate([
            'id'       => 'required|exists:sys_lists,id',
            'name'     => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
        ]);
        sysLists::whereId($request->id)->update([
            'name'     => $request->name,
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
        ]);
        return redirect()->back()->with(['status' => 'List updated successfully', 'stype' => 'success']);
    }

    public function deleteSystemList($id)
    {
        // Sub-lists cascade via FK; delete list items then sys list
        $listIds = lists::where('lid', $id)->pluck('id');
        subList::whereIn('list_id', $listIds)->delete();
        lists::where('lid', $id)->delete();
        sysLists::destroy($id);
        return redirect()->back()->with(['status' => 'List and all items deleted successfully', 'stype' => 'success']);
    }

    // ─── List Items (lists table) ────────────────────────────────────────────

    public function storeListItem(Request $request)
    {
        $request->validate([
            'lid'   => 'required|exists:sys_lists,id',
            'value' => 'required|string|max:255',
            'en'    => 'required|string|max:255',
            'ar'    => 'required|string|max:255',
        ]);

        $data = $request->only(['lid', 'value', 'en', 'ar']);

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('imgs'), $filename);
            $data['img'] = $filename;
        }

        lists::create($data);
        return redirect()->back()->with(['status' => 'Option added successfully', 'stype' => 'success']);
    }

    public function updateListItem(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:lists,id',
            'value' => 'required|string|max:255',
            'en'    => 'required|string|max:255',
            'ar'    => 'required|string|max:255',
        ]);

        $data = $request->only(['value', 'en', 'ar']);

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('imgs'), $filename);
            $data['img'] = $filename;
        }

        lists::whereId($request->id)->update($data);
        return redirect()->back()->with(['status' => 'Option updated successfully', 'stype' => 'success']);
    }

    public function deleteListItem($id)
    {
        subList::where('list_id', $id)->delete();
        lists::destroy($id);
        return redirect()->back()->with(['status' => 'Option deleted successfully', 'stype' => 'success']);
    }

    public function toggleListSub($id)
    {
        $item = lists::findOrFail($id);
        $item->update(['has_sub' => !$item->has_sub]);
        return redirect()->back()->with([
            'status' => 'Sub-lists ' . ($item->has_sub ? 'disabled' : 'enabled'),
            'stype'  => 'success',
        ]);
    }

    // ─── Sub-List Items (sub_lists table) ────────────────────────────────────

    public function storeSubListItem(Request $request)
    {
        $request->validate([
            'list_id' => 'required|exists:lists,id',
            'value'   => 'required|string|max:255',
            'en'      => 'required|string|max:255',
            'ar'      => 'required|string|max:255',
            'price'   => 'nullable|numeric|min:0',
            'img'     => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['list_id', 'value', 'en', 'ar', 'price']);

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('imgs'), $filename);
            $data['img'] = $filename;
        }

        subList::create($data);
        return redirect()->back()->with(['status' => 'Sub-item added successfully', 'stype' => 'success']);
    }

    public function updateSubListItem(Request $request)
    {
        $request->validate([
            'id'      => 'required|exists:sub_lists,id',
            'value'   => 'required|string|max:255',
            'en'      => 'required|string|max:255',
            'ar'      => 'required|string|max:255',
            'price'   => 'nullable|numeric|min:0',
            'img'     => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['value', 'en', 'ar', 'price']);

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('imgs'), $filename);
            $data['img'] = $filename;
        }

        subList::whereId($request->id)->update($data);
        return redirect()->back()->with(['status' => 'Sub-item updated successfully', 'stype' => 'success']);
    }

    public function deleteSubListItem($id)
    {
        subList::destroy($id);
        return redirect()->back()->with(['status' => 'Sub-item deleted successfully', 'stype' => 'success']);
    }

    //=============== system list  Model ==================

    public static function getList($id)
    {
        $syslist = sysLists::whereId($id)->get();
        $lists = lists::where('lid', $id)->get();
        return [$syslist, $lists];
    }

    public static function getLists()
    {
        $syslist = sysLists::get();
        foreach ($syslist as $List) {
            $lists = lists::where('lid', $List->id)->get();
            $List->content = $lists;
        }

        return $syslist;
    }

    public static function getNotifList()
    {
        $notfList = cusNotif::get();
        return $notfList;
    }

    public static function getNotifListBy($actionType)
    {
        $notfList = cusNotif::where('value', $actionType)->get();
        return $notfList;
    }

    //=============== Notifications  Model ==================
    public static function updateNotifList(Request $request)
    {
        $length = count($request->ids);
        $success =
            '(' . $length . ') Notifications Message updated successfully';

        for ($i = 0; $i < $length; $i++) {
            $id = $request->ids[$i];
            $update = cusNotif::whereId($id)->update([
                'title_en' => $request->title_en[$i],
                'title_ar' => $request->title_ar[$i],
                'msg_en'   => $request->msg_en[$i],
                'msg_ar'   => $request->msg_ar[$i],
            ]);
        }

        Session::flash('status', $success);
        Session::flash('stype', 'success');

        return redirect()->back();
    }

    //=============== Features  Model ==================
    public static function getFeaturesByNo($FNO)
    {
        $feature = features::where('no', $FNO)
            ->get();
        return $feature;
    }

    public function updateFeature(Request $request)
    {
        $fid   = $request->fid;
        $value = $request->value;
        $update = date('Y-m-d H:i:s');
        $update = features::whereId($fid)->update([
            'value'      => $value,
            'updated_at' => $update,
        ]);

        $success = 'Features updated';

        Session::flash('status', $success);
        Session::flash('stype', 'success');

        return redirect()->back();
    }
}
