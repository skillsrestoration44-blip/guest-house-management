import client from './client';

export const fetchPublicRooms = (params = {}) => client.get('/api/public/rooms', { params });
export const fetchPublicRoom = (id) => client.get(`/api/public/rooms/${id}`);
export const fetchPublicBranches = () => client.get('/api/public/branches');
export const fetchPublicRoomTypes = () => client.get('/api/public/room-types');
export const fetchPublicServices = () => client.get('/api/public/services');
export const fetchPublicPage = (slug) => client.get(`/api/public/pages/${slug}`);

export const submitOnlineBooking = (payload) => client.post('/api/public/online-booking', payload);
export const lookupOnlineBooking = (payload) => client.post('/api/public/online-booking/status', payload);

export const submitContact = (payload) => client.post('/api/public/contact', payload);

export const switchLocale = (locale) => client.post('/locale/switch', { locale });
