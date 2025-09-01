
import { useState } from "react";
import axiosClient from "../_api/axios";

export default function Settings() {
    const [status, setStatus] = useState("");

    const handleBankSync = async () => {
        setStatus("Syncing...");
        try {
            await axiosClient.post("/bank-sync");
            setStatus("Sync successful!");
        } catch (error) {
            setStatus("Sync failed.");
            console.error(error);
        }
    };

    return (
        <div>
            <header className="bg-white shadow">
                <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 className="text-3xl font-bold text-gray-900">Settings</h1>
                </div>
            </header>
            <div className="mt-8">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow rounded-lg p-5">
                        <h3 className="text-lg leading-6 font-medium text-gray-900">Bank Sync</h3>
                        <div className="mt-5">
                            <button 
                                onClick={handleBankSync}
                                className="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700"
                            >
                                Sync with Bank
                            </button>
                            {status && <p className="mt-2 text-sm text-gray-500">{status}</p>}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
