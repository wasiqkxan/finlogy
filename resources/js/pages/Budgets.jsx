
import { useEffect, useState } from "react";
import axiosClient from "../_api/axios";

export default function Budgets() {
    const [budgets, setBudgets] = useState([]);
    const [showModal, setShowModal] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [currentBudget, setCurrentBudget] = useState(null);
    const [formData, setFormData] = useState({ category: "", amount: "", period: "monthly" });
    const [errors, setErrors] = useState(null);

    useEffect(() => {
        axiosClient.get("/budgets").then(({ data }) => {
            setBudgets(data.data);
        });
    }, []);

    const handleInputChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleAdd = () => {
        setIsEditing(false);
        setFormData({ category: "", amount: "", period: "monthly" });
        setShowModal(true);
    };

    const handleEdit = (budget) => {
        setIsEditing(true);
        setCurrentBudget(budget);
        setFormData({ ...budget });
        setShowModal(true);
    };

    const handleDelete = async (id) => {
        if (window.confirm("Are you sure you want to delete this budget?")) {
            await axiosClient.delete(`/budgets/${id}`);
            setBudgets(budgets.filter((budget) => budget.id !== id));
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        const url = isEditing ? `/budgets/${currentBudget.id}` : "/budgets";
        const method = isEditing ? "put" : "post";

        try {
            const { data } = await axiosClient[method](url, formData);
            if (isEditing) {
                setBudgets(budgets.map((b) => (b.id === currentBudget.id ? data.data : b)));
            } else {
                setBudgets([...budgets, data.data]);
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
                    <h1 className="text-3xl font-bold text-gray-900">Budgets</h1>
                    <button
                        onClick={handleAdd}
                        className="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700"
                    >
                        Add Budget
                    </button>
                </div>
            </header>
            <div className="mt-8">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {budgets.map((budget) => (
                            <div key={budget.id} className="bg-white overflow-hidden shadow rounded-lg p-5">
                                <div className="flex justify-between items-center">
                                    <h3 className="text-lg font-medium text-gray-900">{budget.category}</h3>
                                    <div>
                                        <button onClick={() => handleEdit(budget)} className="text-indigo-600 hover:text-indigo-900">Edit</button>
                                        <button onClick={() => handleDelete(budget.id)} className="ml-4 text-red-600 hover:text-red-900">Delete</button>
                                    </div>
                                </div>
                                <div className="mt-4">
                                    <div className="flex justify-between">
                                        <p className="text-sm text-gray-500">Spent: ${budget.spent}</p>
                                        <p className="text-sm text-gray-500">Budget: ${budget.amount}</p>
                                    </div>
                                    <div className="mt-1 w-full bg-gray-200 rounded-full h-2.5">
                                        <div className="bg-indigo-600 h-2.5 rounded-full" style={{ width: `${(budget.spent / budget.amount) * 100}%` }}></div>
                                    </div>
                                </div>
                            </div>
                        ))}
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
                                 <h3 className="text-lg leading-6 font-medium text-gray-900">{isEditing ? "Edit Budget" : "Add Budget"}</h3>
                                 <div className="mt-2">
                                     <div className="grid grid-cols-1 gap-6">
                                        <input type="text" name="category" value={formData.category} onChange={handleInputChange} placeholder="Category" className="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                                        <input type="number" name="amount" value={formData.amount} onChange={handleInputChange} placeholder="Amount" className="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                                        <select name="period" value={formData.period} onChange={handleInputChange} className="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            <option value="monthly">Monthly</option>
                                            <option value="quarterly">Quarterly</option>
                                            <option value="yearly">Yearly</option>
                                        </select>
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
