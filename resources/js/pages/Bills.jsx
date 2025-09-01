
import { useEffect, useState } from "react";
import axiosClient from "../_api/axios";

export default function Bills() {
    const [bills, setBills] = useState([]);
    const [showModal, setShowModal] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [currentBill, setCurrentBill] = useState(null);
    const [formData, setFormData] = useState({ name: "", amount: "", due_date: "" });
    const [errors, setErrors] = useState(null);

    useEffect(() => {
        axiosClient.get("/bills").then(({ data }) => {
            setBills(data.data);
        });
    }, []);

    const handleInputChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleAdd = () => {
        setIsEditing(false);
        setFormData({ name: "", amount: "", due_date: "" });
        setShowModal(true);
    };

    const handleEdit = (bill) => {
        setIsEditing(true);
        setCurrentBill(bill);
        setFormData({ ...bill });
        setShowModal(true);
    };

    const handleDelete = async (id) => {
        if (window.confirm("Are you sure you want to delete this bill?")) {
            await axiosClient.delete(`/bills/${id}`);
            setBills(bills.filter((bill) => bill.id !== id));
        }
    };

    const handleMarkAsPaid = async (id) => {
        const { data } = await axiosClient.post(`/bills/${id}/pay`);
        setBills(bills.map(b => b.id === id ? data.data : b));
    }

    const handleSubmit = async (e) => {
        e.preventDefault();
        const url = isEditing ? `/bills/${currentBill.id}` : "/bills";
        const method = isEditing ? "put" : "post";

        try {
            const { data } = await axiosClient[method](url, formData);
            if (isEditing) {
                setBills(bills.map((b) => (b.id === currentBill.id ? data.data : b)));
            } else {
                setBills([...bills, data.data]);
            }
            setShowModal(false);
        } catch (error) {
            if (error.response && error.response.status === 422) {
                setErrors(error.response.data.errors);
            } else {
                console.error(error);
            }
        }
    };

    return (
        <div>
            <header className="bg-white shadow">
                <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h1 className="text-3xl font-bold text-gray-900">Bills</h1>
                    <button
                        onClick={handleAdd}
                        className="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700"
                    >
                        Add Bill
                    </button>
                </div>
            </header>
            <div className="mt-8">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow rounded-lg">
                        <div className="p-5">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th className="relative px-6 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {bills.map((bill) => (
                                        <tr key={bill.id}>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{bill.name}</td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${bill.amount}</td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{bill.due_date}</td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm">
                                                <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${bill.paid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>
                                                    {bill.paid ? 'Paid' : 'Unpaid'}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                {!bill.paid && <button onClick={() => handleMarkAsPaid(bill.id)} className="text-indigo-600 hover:text-indigo-900">Mark as Paid</button>}
                                                <button onClick={() => handleEdit(bill)} className="ml-4 text-indigo-600 hover:text-indigo-900">Edit</button>
                                                <button onClick={() => handleDelete(bill.id)} className="ml-4 text-red-600 hover:text-red-900">Delete</button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {showModal && (
                 <div className="fixed z-10 inset-0 overflow-y-auto">
                 <div className="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                     <div className="fixed inset-0 transition-opacity" aria-hidden="true">
                         <div className="absolute inset-0 bg-gray-500 opacity-75"></div>
                     </div>
                     <span className="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                     <div className="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                         <form onSubmit={handleSubmit}>
                             <div className="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                 <h3 className="text-lg leading-6 font-medium text-gray-900">{isEditing ? "Edit Bill" : "Add Bill"}</h3>
                                 <div className="mt-2">
                                     <div className="grid grid-cols-1 gap-6">
                                        <input type="text" name="name" value={formData.name} onChange={handleInputChange} placeholder="Name" className="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                                        <input type="number" name="amount" value={formData.amount} onChange={handleInputChange} placeholder="Amount" className="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                                        <input type="date" name="due_date" value={formData.due_date} onChange={handleInputChange} className="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                                     </div>
                                     {errors && (
                                         <div className="text-red-500 text-sm mt-2">
                                             {Object.values(errors).map((error, index) => (
                                                 <p key={index}>{error[0]}</p>
                                             ))}
                                         </div>
                                     )}
                                 </div>
                             </div>
                             <div className="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                 <button type="submit" className="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                                 <button type="button" onClick={() => setShowModal(false)} className="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                             </div>
                         </form>
                     </div>
                 </div>
             </div>
            )}
        </div>
    );
}
