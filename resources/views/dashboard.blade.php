<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div x-data="{ selectAll: false }" class="overflow-x-auto">
                    <table class="table">
                        <!-- head -->
                        <thead>
                        <tr>
                            <th>
                            <label>
                                <input type="checkbox" class="focus-within:ring-0" x-model="selectAll" />
                            </label>
                            </th>
                            <th>Subject</th>
                            <th>Recepients</th>
                            <th>Status</th>
                            <th></th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- row 1 -->
                        <tr>
                            <th>
                            <label>
                                <input type="checkbox" class="focus-within:ring-0" :checked="selectAll" />
                            </label>
                            </th>
                            <td>
                                <span>Whereas recognition</span>
                            </td>
                            <td>Purple</td>
                            <th>
                            <span class="badge badge-success">Success</span>
                            </th>
                            <th>
                            </th>
                            <th>
                            <button class="btn btn-ghost btn-xs">details</button>
                            </th>
                        </tr>
                        <!-- row 2 -->
                        <tr>
                            <th>
                            <label>
                                <input type="checkbox" class="focus-within:ring-0" :checked="selectAll" />
                            </label>
                            </th>
                            <td>
                                <span>New message</span>
                            </td>
                            <td>Red</td>
                            <th>
                            <span class="badge badge-error">Failed</span>
                            </th>
                            <th>
                            </th>
                            <th>
                            <button class="btn btn-ghost btn-xs">details</button>
                            </th>
                        </tr>
                        <!-- row 3 -->
                        <tr>
                            <th>
                            <label>
                                <input type="checkbox" class="focus-within:ring-0" :checked="selectAll" />
                            </label>
                            </th>
                            <td>
                                <span>New message</span>
                            </td>
                            <td>Crimson</td>
                            <th>
                            <span class="badge badge-success">Success</span>
                            </th>
                            <th>
                            </th>
                            <th>
                            <button class="btn btn-ghost btn-xs">details</button>
                            </th>
                        </tr>
                        <!-- row 4 -->
                        <tr>
                            <th>
                            <label>
                                <input type="checkbox" class="focus-within:ring-0" :checked="selectAll" />
                            </label>
                            </th>
                            <td>
                                <span>New message</span>
                            </td>
                            <td>Indigo</td>
                            <th>
                            <span class="badge badge-success">Success</span>
                            </th>
                            <th>
                            </th>
                            <th>
                            <button class="btn btn-ghost btn-xs">details</button>
                            </th>
                        </tr>
                        </tbody>
                        <!-- foot -->
                        <tfoot>
                        <tr>
                            <th></th>
                            <th>Subject</th>
                            <th>Recepients</th>
                            <th>Status</th>
                            <th></th>
                            <th>Date</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
