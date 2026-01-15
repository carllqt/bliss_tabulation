"use client";
import React, { useState, useEffect } from "react";
import { usePage } from "@inertiajs/react";
import {
    Sidebar,
    SidebarBody,
    SidebarLink,
    Logo,
    LogoIcon,
    SidebarHeader,
} from "@/Components/ui/sidebar";
import { cn } from "@/lib/utils";
import { router } from "@inertiajs/react";
import {
    ListChecks,
    Shirt,
    Droplet,
    Award,
    User,
    Package,
    Star,
    LogOut,
    Trophy,
} from "lucide-react";

export default function SidebarMain({ children }) {
    const [open, setOpen] = useState(false);

    const { props } = usePage();
    const user = props.auth?.user; // ✅ get current logged-in user

    useEffect(() => {
        if (user) {
            console.log("User iD:", user.id);
            console.log("User Name:", user.name);
            console.log("User Role:", user.role);
        } else {
            console.log("No authenticated user found.");
        }
    }, [user]);

    const mainLinks =
        user?.role === "admin"
            ? [
                  {
                      label: "Creative Attire",
                      icon: <ListChecks />,
                      route: "admin.creative_attire",
                  },
                  {
                      label: "Casual Wear",
                      icon: <Shirt />,
                      route: "admin.casual_wear",
                  },
                  {
                      label: "Swim Wear",
                      icon: <Droplet />,
                      route: "admin.swim_wear",
                  },
                  {
                      label: "Talent",
                      icon: <Award />,
                      route: "admin.talent",
                  },
                  {
                      label: "Gown",
                      icon: <Award />,
                      route: "admin.gown",
                  },
                  {
                      label: "Q & A",
                      icon: <User />,
                      route: "admin.q_and_a",
                  },
                  {
                      label: "Beauty",
                      icon: <Award />,
                      route: "admin.beauty",
                  },
                  {
                      label: "Top Five Selection",
                      icon: <Trophy />,
                      route: "admin.top_five_selection",
                  },
              ]
            : [
                  {
                      label: "Creative Attire",
                      icon: <ListChecks />,
                      route: "creative_attire",
                  },
                  {
                      label: "Casual Wear",
                      icon: <Shirt />,
                      route: "casual_wear",
                  },
                  {
                      label: "Swim Wear",
                      icon: <Droplet />,
                      route: "swim_wear",
                  },
                  {
                      label: "Talent",
                      icon: <Award />,
                      route: "talent",
                  },
                  {
                      label: "Gown",
                      icon: <Award />,
                      route: "gown",
                  },
                  {
                      label: "Q & A",
                      icon: <Award />,
                      route: "q_and_a",
                  },
                  {
                      label: "Beauty",
                      icon: <Award />,
                      route: "beauty",
                  },
              ];

    const top5Links =
        user?.role === "admin"
            ? [
                  {
                      label: "Accumulative Scoring",
                      icon: <User />,
                      route: "admin.acumulative_scoring",
                  },
                  {
                      label: "Final Q & A",
                      icon: <Package />,
                      route: "admin.final_q_and_a",
                  },
                  {
                      label: "Top Five Finalist",
                      icon: <Trophy />,
                      route: "admin.top_five_finalist",
                  },
              ]
            : [
                  {
                      label: "Final Q & A",
                      icon: <Package />,
                      route: "final_q_and_a",
                  },
              ];

    return (
        <div className="dark">
            <div
                className={cn(
                    "flex flex-1 flex-col overflow-hidden rounded-md border border-neutral-200 bg-gray-100 md:flex-row dark:border-neutral-700 dark:bg-neutral-800 w-full h-screen"
                )}
            >
                <Sidebar open={open} setOpen={setOpen}>
                    <SidebarBody className="justify-between gap-10">
                        <div className="flex flex-col overflow-x-hidden overflow-y-auto">
                            {/* Logo at the top */}
                            {open ? <Logo /> : <LogoIcon />}

                            {/* Main section header */}
                            <SidebarHeader label="Top 5 Selection" />

                            {/* Main links */}
                            <div className="mt-2 flex flex-col gap-2">
                                {mainLinks.map((link, idx) => {
                                    const iconElement = React.cloneElement(
                                        link.icon,
                                        {
                                            className:
                                                "h-5 w-5 shrink-0 text-neutral-700 dark:text-neutral-200",
                                        }
                                    );

                                    return (
                                        <SidebarLink
                                            key={idx}
                                            link={{
                                                label: link.label,
                                                icon: iconElement,
                                                href: "#", // href is required but will be handled via onClick
                                                className:
                                                    "text-neutral-700 dark:text-neutral-200",
                                                onClick: (e) => {
                                                    e.preventDefault();
                                                    router.get(
                                                        route(link.route)
                                                    );
                                                },
                                            }}
                                        />
                                    );
                                })}
                            </div>

                            {/* Top 5 section header */}
                            <SidebarHeader label="Top 5 Finalist" />

                            {/* Top 5 links */}
                            <div className="mt-2 flex flex-col gap-2">
                                {top5Links.map((link, idx) => {
                                    const iconElement = React.cloneElement(
                                        link.icon,
                                        {
                                            className:
                                                "h-5 w-5 shrink-0 text-neutral-700 dark:text-neutral-200",
                                        }
                                    );

                                    return (
                                        <SidebarLink
                                            key={idx}
                                            link={{
                                                label: link.label,
                                                icon: iconElement,
                                                href: "#", // required prop
                                                className:
                                                    "text-neutral-700 dark:text-neutral-200",
                                                onClick: (e) => {
                                                    e.preventDefault();
                                                    router.get(
                                                        route(link.route)
                                                    );
                                                },
                                            }}
                                        />
                                    );
                                })}
                            </div>
                        </div>

                        <div className="flex flex-col gap-2">
                            <SidebarLink
                                link={{
                                    label: `${
                                        user?.role === "admin"
                                            ? "Admin"
                                            : "Judge"
                                    }: ${user?.name || "User"}`,
                                    href: "#",
                                    icon: (
                                        <img
                                            src="/isu-logo.png"
                                            className="h-7 w-7 shrink-0 rounded-full"
                                            width={50}
                                            height={50}
                                            alt="Avatar"
                                        />
                                    ),
                                    className:
                                        "text-neutral-700 dark:text-neutral-200",
                                }}
                            />
                            <SidebarLink
                                link={{
                                    label: "Logout",
                                    href: "#",
                                    icon: (
                                        <LogOut className="h-5 w-5 shrink-0 text-neutral-700 dark:text-neutral-200" />
                                    ),
                                    onClick: (e) => {
                                        e.preventDefault();
                                        router.post("/logout");
                                    },
                                    className:
                                        "text-neutral-700 dark:text-neutral-200",
                                }}
                            />
                        </div>
                    </SidebarBody>
                </Sidebar>

                <div className="flex-1 flex flex-col h-full border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-900 overflow-y-auto">
                    {children}
                </div>
            </div>
        </div>
    );
}
