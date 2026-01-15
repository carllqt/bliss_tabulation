"use client";

import React from "react";
import PageLayout from "@/Layouts/PageLayout";
import TopFiveSelectionTable from "@/Pages/Admin/Partials/TopFiveSelectionTable";

const TotalResults = ({ categoryName = "Total Results", candidates = [] }) => {
    return (
        <PageLayout>
            <h2 className="text-white text-xl font-bold mb-6 justify-center flex mt-6">
                {categoryName}
            </h2>

            <TopFiveSelectionTable
                candidates={candidates}
                categories={["accumulative", "final_q_and_a"]}
                category={`${categoryName} Results`}
            />
        </PageLayout>
    );
};

export default TotalResults;
