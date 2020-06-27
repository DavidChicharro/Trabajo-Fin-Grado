export interface Notification {
    id: string
    senderId: string;
    senderName: string;
    senderEmail: string;
    recipientId: string;
    recipientName: string;
    notificationType: string;
    message: string;
}