export type FaqItem = {
    question: string;
    answer: string;
};

export type FaqBlock = {
    title: string;
    description: string;
    eyebrow: string;
    items: FaqItem[];
};
