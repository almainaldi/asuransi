@extends('dashboard')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
  <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
    
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold text-gray-800">Master Data Pelanggan</h2>
      <a href="{{ route('create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm transition duration-150 ease-in-out">
        Create New
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usia</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @foreach ($customers as $customer)
          <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->email }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->address }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->age }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-2">
              <a href="{{ route('update', ['customer_id'=>$customer->id] ) }}" class="text-amber-600 hover:text-amber-900 bg-amber-100 px-3 py-1 rounded">Update</a>
              <form action="{{ route('delete', ['customer_id'=>$customer->id] ) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 px-3 py-1 rounded" onclick="return confirm('Yakin ingin menghapus?')">Delete</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection